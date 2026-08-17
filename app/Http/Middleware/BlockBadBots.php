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
        $uri = rawurldecode($request->getRequestUri());
        $maliciousPatterns = [
            '/(\%27|\'|\"|\%22).*(or|and|union|select|insert|update|delete|drop|truncate|alter|exec|concat|information_schema)/i',
            '/\b(union\s+select|select\s+.*\s+from|insert\s+into|delete\s+from|drop\s+table)\b/i',
            '/(\%27|\')\s*OR\s*1\s*=\s*1/i',
            '/test\s*[\'"]\s*or\s*1\s*=\s*1/i',
            '/<script|javascript:|onerror\s*=|onload\s*=/i',
            '/\/etc\/passwd|\/etc\/shadow|\.\.\/\.\.\//i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $uri) || preg_match($pattern, http_build_query($request->all()))) {
                // Khóa IP tự động trong 24 giờ (86,400 giây)
                Cache::put('banned_ip_' . $ip, true, now()->addHours(24));
                
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

        // 4. Theo dõi và giới hạn số lượng Session khách vãng lai (> 10 session / IP)
        if (!$isGoodBot && !Auth::check()) {
            // Bỏ qua kiểm tra giới hạn đối với các đường dẫn đăng nhập/đăng ký/static assets/webhooks
            $path = ltrim($request->path(), '/');
            $exemptPaths = ['login', 'register', 'password', 'auth', 'webhook'];
            $isExempt = false;

            foreach ($exemptPaths as $exempt) {
                if (str_starts_with($path, $exempt)) {
                    $isExempt = true;
                    break;
                }
            }

            if (!$isExempt && $request->hasSession()) {
                $sessionId = $request->session()->getId();
                if ($sessionId) {
                    $cacheKey = 'guest_sessions_' . md5($ip);
                    $guestSessions = Cache::get($cacheKey, []);

                    if (!in_array($sessionId, $guestSessions, true)) {
                        $guestSessions[] = $sessionId;
                        Cache::put($cacheKey, $guestSessions, now()->addHours(6));
                    }

                    // Nếu IP phát sinh trên 10 session khách vãng lai khác nhau
                    if (count($guestSessions) > 10) {
                        // Tự động khóa IP 24h khi vượt quá 10 session rác liên tục
                        Cache::put('banned_ip_' . $ip, true, now()->addHours(24));

                        // Gửi thông báo Telegram về IP spam session nghi ngờ (Throttle 1 giờ / 1 IP)
                        if (!Cache::has('telegram_notified_ip_' . $ip)) {
                            Cache::put('telegram_notified_ip_' . $ip, true, now()->addHour());
                            \App\Helpers\TelegramHelper::sendSuspiciousIpNotification(
                                $ip,
                                'Phát hiện phát sinh hơn 10 phiên (session) khách vãng lai dồn dập từ cùng 1 IP (Đã tự động ngắt kết nối & khóa 24h)',
                                $userAgent,
                                $request->fullUrl()
                            );
                        }

                        if ($request->expectsJson() || $request->is('api/*')) {
                            return response()->json([
                                'message' => 'Phát hiện quá nhiều phiên kết nối từ IP của bạn. Vui lòng đăng nhập để tiếp tục.'
                            ], 403);
                        }

                        // Chuyển hướng người dùng đến trang đăng nhập với thông báo
                        return redirect()->route('login')->with('error', 'Hệ thống phát hiện quá 10 phiên khách vãng lai từ địa chỉ IP này. Vui lòng đăng nhập tài khoản để tiếp tục sử dụng.');
                    }
                }
            }
        }

        return $next($request);
    }
}
