<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnlineSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OnlineUserController extends Controller
{
    /**
     * Get current count of active online users (logged-in and guests)
     */
    public function count()
    {
        $minutes = 5;
        $activeQuery = OnlineSession::active($minutes);

        $totalCount = (clone $activeQuery)->count();
        $loggedInCount = (clone $activeQuery)->loggedIn()->count();
        $guestCount = (clone $activeQuery)->guests()->count();

        // Optional baseline offset from SiteSetting if configured by admin (default 0)
        $offset = (int) \App\Models\SiteSetting::getValue('online_users_offset', 0);
        $displayCount = max(1, $totalCount + $offset);

        return response()->json([
            'success' => true,
            'count' => $displayCount,
            'real_count' => $totalCount,
            'logged_in_count' => $loggedInCount,
            'guest_count' => $guestCount,
            'formatted' => $displayCount . ' đang xem',
        ]);
    }

    /**
     * Ping heartbeat to refresh user last_activity timestamp while viewing
     */
    public function ping(Request $request)
    {
        try {
            $sessionId = $request->session()->getId();
            if ($sessionId) {
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
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return $this->count();
    }
}
