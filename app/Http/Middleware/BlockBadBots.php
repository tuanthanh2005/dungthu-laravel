<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class BlockBadBots
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        if (!$ip) {
            return $next($request);
        }

        // 1. Kiểm tra IP đã bị ban chưa (Trong Cache hoặc CSDL)
        $isBanned = Cache::has('banned_ip_' . $ip);

        if (!$isBanned) {
            $isBanned = Cache::remember('db_banned_ip_' . $ip, 120, function () use ($ip) {
                return \App\Models\BannedIp::where('ip_address', $ip)
                    ->where(function ($q) {
                        $q->whereNull('banned_until')->orWhere('banned_until', '>', now());
                    })
                    ->exists();
            });

            if ($isBanned) {
                Cache::put('banned_ip_' . $ip, true, now()->addHours(1));
            }
        }

        if ($isBanned) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Địa chỉ IP của bạn đã bị truy cập giới hạn hoặc bị khóa bởi Quản trị viên.'
                ], 403);
            }
            return response('<h1>403 Forbidden</h1><p>Địa chỉ IP của bạn ('.$ip.') đã bị khóa truy cập bởi Quản trị viên hoặc do hệ thống bảo mật ngắt kết nối.</p>', 403);
        }

        $userAgent = (string) $request->userAgent();
        $uaLower = strtolower($userAgent);

        // Danh sách bot tìm kiếm hợp lệ (Cho phép đi qua)
        $goodBots = [
            'googlebot',
            'bingbot',
            'yandexbot',
            'facebookexternalhit',
            'duckduckbot',
            'twitterbot',
            'baiduspider',
            'slurp',
            'linkedinbot',
            'telegrambot',
        ];

        $isGoodBot = false;
        foreach ($goodBots as $bot) {
            if (str_contains($uaLower, $bot)) {
                $isGoodBot = true;
                break;
            }
        }

        // 2. Chặn các User-Agent từ tool/script tự động hoặc User-Agent rỗng (Nếu không phải Good Bot)
        if (!$isGoodBot) {
            if (empty(trim($userAgent))) {
                return response('Access Denied: Missing User-Agent', 403);
            }

            $badBotPatterns = '/(curl|python|wget|sqlmap|nikto|nmap|dirbuster|go-http-client|httpclient|java|php-requests|libwww|httpx|masscan|zgrab|acunetix|nessus)/i';
            if (preg_match($badBotPatterns, $uaLower)) {
                return response('Access Denied: Suspicious User-Agent Detected', 403);
            }
        }

        // 3. Phát hiện chuỗi tấn công SQL Injection / XSS / Directory Traversal trong URL & Params
        // Bỏ qua kiểm tra rà quét dữ liệu FORM đối với các đường dẫn quản trị (admin*) hoặc Admin đã đăng nhập
        $isAdminRequest = $request->is('admin*') || (Auth::check() && optional(Auth::user())->is_admin);

        if (!$isAdminRequest) {
            $uri = rawurldecode($request->getRequestUri());
            $maliciousPatterns = [
                '/\b(union\s+all\s+select|union\s+select|select\s+.*\s+from|insert\s+into|delete\s+from|drop\s+table|truncate\s+table|alter\s+table)\b/i',
                '/(\%27|\')\s*OR\s*[\'\"]?\d+[\'\"]?\s*=\s*[\'\"]?\d+/i',
                '/test\s*[\'"]\s*or\s*1\s*=\s*1/i',
                '/<script[\s>]|javascript:|onerror\s*=|onload\s*=/i',
                '/\/etc\/passwd|\/etc\/shadow|\.\.\/\.\.\//i',
            ];

            $queryString = !empty($request->all()) ? http_build_query($request->all()) : '';
            foreach ($maliciousPatterns as $pattern) {
                if (preg_match($pattern, $uri) || ($queryString !== '' && preg_match($pattern, $queryString))) {
                    // Khóa IP tự động trong 24 giờ (86,400 giây)
                    Cache::put('banned_ip_' . $ip, true, now()->addHours(24));
                    
                    // Tự động lưu nhật ký báo đỏ vào CSDL
                    try {
                        \App\Models\SuspiciousIpLog::updateOrCreate(
                            ['ip_address' => $ip],
                            [
                                'reason' => 'Tấn công / rà quét lỗ hổng SQL Injection hoặc XSS',
                                'url' => substr($request->fullUrl(), 0, 500),
                                'user_agent' => substr($userAgent, 0, 500),
                                'status' => 'auto_banned_24h',
                                'banned_until' => now()->addHours(24),
                            ]
                        );
                    } catch (\Throwable $e) {}

                    // Gửi thông báo Telegram về IP đáng nghi ngờ (Throttle 1 giờ / 1 IP)
                    if (!Cache::has('telegram_notified_ip_' . $ip)) {
                        Cache::put('telegram_notified_ip_' . $ip, true, now()->addHour());
                        \App\Helpers\TelegramHelper::sendSuspiciousIpNotification(
                            $ip,
                            'Phát hiện tấn công / rà quét lỗ hổng SQL Injection hoặc XSS (Hệ thống đã tự động khóa IP 24h)',
                            $userAgent,
                            $request->fullUrl()
                        );
                    }

                    return response('<h1>403 Access Denied</h1><p>Hành vi tấn công hoặc quét lỗ hổng đã bị phát hiện. Địa chỉ IP của bạn bị khóa 24 giờ.</p>', 403);
                }
            }
        }

        // 4. Theo dõi thời gian (5 phút) và số lượng Session (> 10 session) đối với Khách vãng lai
        if (!$isGoodBot && !Auth::check()) {
            // Bỏ qua kiểm tra giới hạn đối với các đường dẫn đăng nhập/đăng ký/static assets/webhooks/API
            $path = ltrim($request->path(), '/');
            $exemptPaths = ['login', 'register', 'password', 'auth', 'webhook', 'guest-chat', 'api/online-users', 'api/telegram'];
            $isExempt = false;

            foreach ($exemptPaths as $exempt) {
                if (str_starts_with($path, $exempt) || str_contains($path, $exempt)) {
                    $isExempt = true;
                    break;
                }
            }

            if (!$isExempt && $request->hasSession()) {
                $session = $request->session();

                // 4A. Kiểm tra thời gian duyệt web của Khách vãng lai (Giới hạn 5 phút = 300 giây)
                if (!$session->has('guest_first_seen_at')) {
                    $session->put('guest_first_seen_at', time());
                }

                $firstSeen = (int) $session->get('guest_first_seen_at');
                if ($firstSeen > 0 && (time() - $firstSeen) >= 300) {
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json([
                            'message' => 'Bạn đã trải nghiệm xem web 5 phút với tư cách Khách vãng lai. Vui lòng đăng nhập để tiếp tục.'
                        ], 403);
                    }

                    return redirect()->route('login')->with('info', '⏰ Bạn đã trải nghiệm xem trang web 5 phút với tư cách Khách vãng lai. Vui lòng đăng nhập hoặc tạo tài khoản miễn phí để tiếp tục lướt xem và sử dụng dịch vụ trên DungThu.com!');
                }

                // 4B. Kiểm tra số lượng Session khách vãng lai (> 10 session / IP)
                $sessionId = $session->getId();
                if ($sessionId) {
                    $cacheKey = 'guest_sessions_' . md5($ip);
                    $guestSessions = Cache::get($cacheKey, []);

                    if (!in_array($sessionId, $guestSessions, true)) {
                        $guestSessions[] = $sessionId;
                        Cache::put($cacheKey, $guestSessions, now()->addHours(6));
                    }

                    // Nếu IP phát sinh trên 10 session khách vãng lai khác nhau -> Yêu cầu Đăng nhập (KHÔNG khóa IP)
                    if (count($guestSessions) > 10) {
                        if ($request->expectsJson() || $request->is('api/*')) {
                            return response()->json([
                                'message' => 'Phát hiện nhiều phiên kết nối từ IP của bạn. Vui lòng đăng nhập tài khoản để tiếp tục truy cập website.'
                            ], 403);
                        }

                        // Chuyển hướng người dùng đến trang đăng nhập với thông báo yêu cầu đăng nhập
                        return redirect()->route('login')->with('warning', '⚠️ Phát hiện nhiều phiên kết nối khách từ IP này. Vui lòng Đăng nhập tài khoản để tiếp tục truy cập website!');
                    }
                }
            }
        }

        return $next($request);
    }
}
