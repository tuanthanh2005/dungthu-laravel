<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OnlineSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TrackOnlineUsers
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip tracking for asset requests, AJAX polling, API endpoints, background scripts
        if ($this->shouldSkip($request)) {
            return $response;
        }

        try {
            // Check if online_sessions table exists in database (Cached to avoid DB query on every hit)
            $hasTable = Cache::rememberForever('schema_has_online_sessions', fn () => Schema::hasTable('online_sessions'));
            if (!$hasTable) {
                return $response;
            }

            if (!$request->hasSession()) {
                return $response;
            }

            $sessionId = $request->session()->getId();
            if (!$sessionId) {
                return $response;
            }

            $currentUrl = substr($request->fullUrl(), 0, 255);
            $lastTrackedUrl = $request->session()->get('online_tracked_url');
            $lastTrackedTime = $request->session()->get('online_tracked_at');
            $now = time();

            // Optimization: Skip DB write if on same URL and updated within last 30 seconds
            if ($lastTrackedUrl === $currentUrl && $lastTrackedTime && ($now - $lastTrackedTime) < 30) {
                return $response;
            }

            $userId = Auth::id();
            $ipAddress = $request->ip();
            $userAgent = substr((string) $request->userAgent(), 0, 500);
            $deviceType = $this->detectDevice($userAgent);

            // Perform upsert for online_sessions
            OnlineSession::updateOrCreate(
                ['session_id' => $sessionId],
                [
                    'user_id' => $userId,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                    'current_url' => $currentUrl,
                    'last_activity' => Carbon::now(),
                ]
            );

            // Remember in session to throttle redundant DB writes
            $request->session()->put('online_tracked_url', $currentUrl);
            $request->session()->put('online_tracked_at', $now);

            // Garbage collection: 1% chance to purge sessions older than 3 months (90 days, limited to 500 rows)
            if (rand(1, 100) === 1) {
                OnlineSession::where('last_activity', '<', Carbon::now()->subDays(90))->limit(500)->delete();
            }
        } catch (\Throwable $e) {
            // Silently ignore tracking errors to avoid disrupting user experience
        }

        return $response;
    }

    /**
     * Determine if request should be skipped for tracking
     */
    private function shouldSkip(Request $request): bool
    {
        // Skip non-GET requests
        if (!$request->isMethod('GET')) {
            return true;
        }

        // Skip bots, crawlers, scanners, and command line tools
        $userAgent = strtolower((string) $request->userAgent());
        if (empty(trim($userAgent)) || preg_match('/(curl|python|wget|sqlmap|nikto|bot|crawler|spider|scan)/i', $userAgent)) {
            return true;
        }

        // Skip AJAX / JSON fetch requests (e.g. background polling calls like sidebar-counters)
        if ($request->ajax() || $request->wantsJson() || $request->isXmlHttpRequest()) {
            return true;
        }

        $path = ltrim($request->path(), '/');

        // Ignore API routes, asset routes, image routes, debugbar, websocket, background AJAX paths
        $ignorePrefixes = [
            'api/',
            'admin/sidebar-counters',
            'admin/chat/unread-count',
            'admin/chat/messages',
            'storage/',
            'images/',
            'build/',
            'favicon',
            'guest-chat',
            '_debugbar',
            'livewire',
        ];

        foreach ($ignorePrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        // Ignore static asset extensions
        if (preg_match('/\.(png|jpg|jpeg|gif|svg|css|js|ico|woff|woff2|ttf|eot)$/i', $path)) {
            return true;
        }

        return false;
    }

    /**
     * Simple device detector from user agent string
     */
    private function detectDevice(?string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'desktop';
        }

        $ua = strtolower($userAgent);

        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet') || str_contains($ua, 'playbook') || str_contains($ua, 'silk')) {
            return 'tablet';
        }

        if (str_contains($ua, 'mobile') || str_contains($ua, 'iphone') || str_contains($ua, 'android') || str_contains($ua, 'phone') || str_contains($ua, 'ipod')) {
            return 'mobile';
        }

        return 'desktop';
    }
}
