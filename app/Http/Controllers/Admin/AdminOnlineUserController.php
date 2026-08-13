<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnlineSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminOnlineUserController extends Controller
{
    /**
     * Display list of currently active visitors (logged-in and guest)
     * Supports Tab "online" (real-time) and Tab "manage" (daily stats & history)
     */
    public function index(Request $request)
    {
        // Periodic auto cleanup: delete records older than 90 days (3 months)
        try {
            OnlineSession::where('last_activity', '<', Carbon::now()->subDays(90))->delete();
        } catch (\Throwable $e) {
            // Ignore DB errors if table missing
        }

        $activeTab = $request->get('tab', 'online');

        // Stats for Tab 1 (Online real-time)
        $liveQuery = OnlineSession::where('last_activity', '>=', Carbon::now()->subMinutes(5));
        $totalOnline = (clone $liveQuery)->count();
        $loggedInCount = (clone $liveQuery)->whereNotNull('user_id')->count();
        $guestCount = (clone $liveQuery)->whereNull('user_id')->count();

        $topPages = OnlineSession::where('last_activity', '>=', Carbon::now()->subMinutes(15))
            ->select('current_url', DB::raw('count(*) as count'))
            ->groupBy('current_url')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        if ($activeTab === 'manage') {
            // Tab 2: Quản lý & Thống kê
            $todayVisitors = OnlineSession::whereDate('last_activity', Carbon::today())->count();
            $yesterdayVisitors = OnlineSession::whereDate('last_activity', Carbon::yesterday())->count();
            $thisMonthVisitors = OnlineSession::whereMonth('last_activity', Carbon::now()->month)
                ->whereYear('last_activity', Carbon::now()->year)
                ->count();

            // Daily breakdown table for last 14 days
            $dailyStats = OnlineSession::where('last_activity', '>=', Carbon::now()->subDays(14))
                ->select(
                    DB::raw('DATE(last_activity) as date'),
                    DB::raw('count(*) as total'),
                    DB::raw('count(user_id) as logged_in'),
                    DB::raw('count(case when user_id is null then 1 end) as guests')
                )
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();

            // Top Visited Pages ranking (sorted descending by total views) with pagination
            $topVisitedPages = OnlineSession::select(
                    'current_url',
                    DB::raw('count(*) as total_views'),
                    DB::raw('count(user_id) as logged_in_views'),
                    DB::raw('count(case when user_id is null then 1 end) as guest_views'),
                    DB::raw('max(last_activity) as last_visited_at')
                )
                ->whereNotNull('current_url')
                ->where('current_url', '!=', '')
                ->groupBy('current_url')
                ->orderBy('total_views', 'desc')
                ->paginate(10, ['*'], 'top_page')
                ->withQueryString();

            // Query for historical log list with date range & search filters
            $manageQuery = OnlineSession::with('user');

            if ($request->filled('date_from')) {
                $manageQuery->whereDate('last_activity', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $manageQuery->whereDate('last_activity', '<=', $request->date_to);
            }
            if ($request->filled('type')) {
                if ($request->type === 'logged_in') {
                    $manageQuery->whereNotNull('user_id');
                } elseif ($request->type === 'guests') {
                    $manageQuery->whereNull('user_id');
                }
            }
            if ($request->filled('search')) {
                $search = trim($request->search);
                $manageQuery->where(function ($q) use ($search) {
                    $q->where('ip_address', 'like', "%{$search}%")
                      ->orWhere('current_url', 'like', "%{$search}%")
                      ->orWhere('user_agent', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($u) use ($search) {
                          $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $sessions = $manageQuery->orderBy('last_activity', 'desc')->paginate(20)->withQueryString();

            return view('admin.online_users.index', compact(
                'activeTab',
                'sessions',
                'totalOnline',
                'loggedInCount',
                'guestCount',
                'topPages',
                'todayVisitors',
                'yesterdayVisitors',
                'thisMonthVisitors',
                'dailyStats',
                'topVisitedPages'
            ));
        }

        // Default Tab 1: Live Online Users (active within last 10 min)
        $query = OnlineSession::with('user')
            ->where('last_activity', '>=', Carbon::now()->subMinutes(10));

        if ($request->filled('type')) {
            if ($request->type === 'logged_in') {
                $query->whereNotNull('user_id');
            } elseif ($request->type === 'guests') {
                $query->whereNull('user_id');
            }
        }

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

        $sessions = $query->orderBy('last_activity', 'desc')->paginate(20)->withQueryString();

        return view('admin.online_users.index', compact(
            'activeTab',
            'sessions',
            'totalOnline',
            'loggedInCount',
            'guestCount',
            'topPages'
        ));
    }

    /**
     * Export online users / visitor history to Excel-compatible CSV (max 100 rows per batch)
     */
    public function exportExcel(Request $request)
    {
        $query = OnlineSession::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('last_activity', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('last_activity', '<=', $request->date_to);
        }
        if ($request->filled('type')) {
            if ($request->type === 'logged_in') {
                $query->whereNotNull('user_id');
            } elseif ($request->type === 'guests') {
                $query->whereNull('user_id');
            }
        }
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

        // Limit strictly to 100 users per export batch as requested
        $exportData = $query->orderBy('last_activity', 'desc')->limit(100)->get();

        $fileName = 'thong-ke-khach-truy-cap-' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($exportData) {
            $file = fopen('php://output', 'w');

            // Output UTF-8 BOM so Excel opens Vietnamese characters seamlessly
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Column Headers
            fputcsv($file, [
                'STT',
                'Loại khách hàng',
                'Tên / Email / Session ID',
                'Địa chỉ IP',
                'Thiết bị',
                'Trình duyệt (User Agent)',
                'Trang đang xem (URL)',
                'Tương tác gần nhất',
                'Thời gian vào đầu tiên',
            ]);

            foreach ($exportData as $index => $row) {
                $userType = $row->user ? 'Thành viên đã đăng nhập' : 'Khách chưa đăng nhập (Vãng lai)';
                $userInfo = $row->user ? "{$row->user->name} ({$row->user->email})" : "Khách vãng lai (#" . substr($row->session_id, 0, 10) . ")";
                $lastActive = $row->last_activity ? $row->last_activity->format('H:i:s d/m/Y') : '';
                $createdAt = $row->created_at ? $row->created_at->format('H:i:s d/m/Y') : '';

                fputcsv($file, [
                    $index + 1,
                    $userType,
                    $userInfo,
                    $row->ip_address,
                    strtoupper($row->device_type ?: 'desktop'),
                    $row->user_agent,
                    $row->current_url,
                    $lastActive,
                    $createdAt,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
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
