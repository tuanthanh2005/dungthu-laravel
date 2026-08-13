<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnlineSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class OnlineUserController extends Controller
{
    /**
     * Get current count of active online users (logged-in and guests)
     * Cached for 10 seconds to eliminate DB overhead on high-traffic sites
     */
    public function count()
    {
        try {
            if (!Schema::hasTable('online_sessions')) {
                return response()->json([
                    'success' => true,
                    'count' => 1,
                    'real_count' => 1,
                    'logged_in_count' => 0,
                    'guest_count' => 1,
                    'formatted' => '1 đang xem',
                ]);
            }

            $stats = Cache::remember('online_users_stats_cache', 10, function () {
                $minutes = 5;
                $activeQuery = OnlineSession::active($minutes);

                $totalCount = (clone $activeQuery)->count();
                $loggedInCount = (clone $activeQuery)->loggedIn()->count();
                $guestCount = (clone $activeQuery)->guests()->count();

                return [
                    'total_count' => $totalCount,
                    'logged_in_count' => $loggedInCount,
                    'guest_count' => $guestCount,
                ];
            });

            // Optional baseline offset from SiteSetting if configured by admin (default 0)
            $offset = (int) \App\Models\SiteSetting::getValue('online_users_offset', 0);
            $displayCount = max(1, $stats['total_count'] + $offset);

            return response()->json([
                'success' => true,
                'count' => $displayCount,
                'real_count' => $stats['total_count'],
                'logged_in_count' => $stats['logged_in_count'],
                'guest_count' => $stats['guest_count'],
                'formatted' => $displayCount . ' đang xem',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'count' => 1,
                'real_count' => 1,
                'logged_in_count' => 0,
                'guest_count' => 1,
                'formatted' => '1 đang xem',
            ]);
        }
    }

    /**
     * Ping heartbeat to refresh user last_activity timestamp while viewing
     */
    public function ping(Request $request)
    {
        try {
            if (Schema::hasTable('online_sessions') && $request->hasSession()) {
                $sessionId = $request->session()->getId();
                $lastPing = $request->session()->get('online_last_ping_at');
                $now = time();

                // Only update DB if last ping was more than 30 seconds ago
                if ($sessionId && (!$lastPing || ($now - $lastPing) >= 30)) {
                    OnlineSession::updateOrCreate(
                        ['session_id' => $sessionId],
                        [
                            'user_id' => Auth::id(),
                            'ip_address' => $request->ip(),
                            'user_agent' => substr((string) $request->userAgent(), 0, 500),
                            'current_url' => substr($request->fullUrl(), 0, 255),
                            'last_activity' => Carbon::now(),
                        ]
                    );

                    $request->session()->put('online_last_ping_at', $now);
                }
            }
        } catch (\Throwable $e) {
            // Ignore
        }

        return $this->count();
    }
}
