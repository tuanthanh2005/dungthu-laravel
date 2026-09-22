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

        // Bỏ qua hoàn toàn kiểm tra bot/ua đối với các đường dẫn Webhook (Telegram, SePay...)
        if ($request->is('api/telegram/*') || $request->is('webhook/*')) {
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

        // Guests may browse freely, but after five minutes they must sign in
        // before performing a state-changing action.  Do not redirect normal
        // page views: doing that made the home page appear intermittently
        // blank.  Do not use an IP/session-count limit here, because mobile
        // networks and offices commonly share an IP.
        if (!$isGoodBot && !Auth::check() && $request->hasSession()) {
            $session = $request->session();
            $firstSeen = (int) $session->get('guest_first_seen_at', 0);

            if ($firstSeen === 0) {
                $firstSeen = time();
                $session->put('guest_first_seen_at', $firstSeen);
            }

            $isSafeMethod = in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
            $isAuthOrInfrastructureRequest = $request->is(
                'login',
                'register',
                'forgot-password',
                'reset-password',
                'auth/*',
                'webhook/*',
                'api/online-users/*',
                'api/telegram/*'
            );

            if (!$isSafeMethod && !$isAuthOrInfrastructureRequest && (time() - $firstSeen) >= 300) {
                $message = 'Phiên trải nghiệm miễn phí đã hết. Vui lòng đăng nhập để tiếp tục thao tác.';

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => $message], 401);
                }

                return redirect()->route('login')->with('info', $message);
            }
        }

        return $next($request);
    }
}
