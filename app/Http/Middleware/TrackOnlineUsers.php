<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OnlineSession;
use Illuminate\Support\Facades\Auth;
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
            $sessionId = $request->session()->getId();
            if (!$sessionId) {
                return $response;
            }

            $userId = Auth::id();
            $ipAddress = $request->ip();
            $userAgent = substr((string) $request->userAgent(), 0, 500);
            $deviceType = $this->detectDevice($userAgent);

            $currentUrl = $request->fullUrl();

            // Perform upsert for online_sessions
            OnlineSession::updateOrCreate(
                ['session_id' => $sessionId],
                [
                    'user_id' => $userId,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                    'current_url' => substr($currentUrl, 0, 255),
                    'last_activity' => Carbon::now(),
                ]
            );

            // Garbage collection: 1% chance to purge sessions older than 2 hours
            if (rand(1, 100) === 1) {
                OnlineSession::where('last_activity', '<', Carbon::now()->subHours(2))->delete();
            }
        } catch (\Exception $e) {
            // Silently ignore tracking errors to avoid disrupting user experience
        }

        return $response;
    }

    /**
     * Determine if request should be skipped for tracking
     */
    private function shouldSkip(Request $request): bool
    {
        // Skip non-GET requests or AJAX background calls except standard page navigation
        if (!$request->isMethod('GET')) {
            return true;
        }

        $path = ltrim($request->path(), '/');

        // Ignore API routes, asset routes, image routes, debugbar, websocket, etc.
        $ignorePrefixes = [
            'api/',
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
