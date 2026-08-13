<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnlineSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminOnlineUserController extends Controller
{
    /**
     * Display list of currently active visitors (logged-in and guest)
     */
    public function index(Request $request)
    {
        $minutes = 10; // View sessions active within last 10 minutes
        $query = OnlineSession::with('user')
            ->where('last_activity', '>=', Carbon::now()->subMinutes($minutes));

        // Filter by user type
        if ($request->filled('type')) {
            if ($request->type === 'logged_in') {
                $query->whereNotNull('user_id');
            } elseif ($request->type === 'guests') {
                $query->whereNull('user_id');
            }
        }

        // Search by keyword (name, email, IP, URL)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('current_url', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by last activity descending
        $sessions = $query->orderBy('last_activity', 'desc')->paginate(20)->withQueryString();

        // Statistics (5 min threshold for strictly live online count)
        $liveQuery = OnlineSession::where('last_activity', '>=', Carbon::now()->subMinutes(5));
        $totalOnline = (clone $liveQuery)->count();
        $loggedInCount = (clone $liveQuery)->whereNotNull('user_id')->count();
        $guestCount = (clone $liveQuery)->whereNull('user_id')->count();

        // Top viewed URLs right now
        $topPages = OnlineSession::where('last_activity', '>=', Carbon::now()->subMinutes(15))
            ->select('current_url', DB::raw('count(*) as count'))
            ->groupBy('current_url')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.online_users.index', compact(
            'sessions',
            'totalOnline',
            'loggedInCount',
            'guestCount',
            'topPages'
        ));
    }

    /**
     * Terminate/kick an active online session
     */
    public function kick($id)
    {
        $session = OnlineSession::findOrFail($id);
        $session->delete();

        return redirect()->back()->with('success', 'Đã ngắt phiên làm việc của khách hàng thành công.');
    }

    /**
     * Delete/terminate an active online session
     */
    public function destroy($id)
    {
        return $this->kick($id);
    }
}
