<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuspiciousIpLog;
use App\Models\BannedIp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SuspiciousIpController extends Controller
{
    public function index(Request $request)
    {
        $query = SuspiciousIpLog::query();

        // Thống kê nhanh số lượng theo trạng thái
        $totalLogs = SuspiciousIpLog::count();
        $autoBannedCount = SuspiciousIpLog::where('status', 'auto_banned_24h')->count();
        $permanentlyBannedCount = SuspiciousIpLog::where('status', 'permanently_banned')->count();
        $safeCount = SuspiciousIpLog::where('status', 'safe')->count();

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tìm kiếm IP, lý do, URL, User-Agent
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.suspicious_ips.index', compact(
            'logs',
            'totalLogs',
            'autoBannedCount',
            'permanentlyBannedCount',
            'safeCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:safe,permanently_banned,24h_banned',
        ]);

        $log = SuspiciousIpLog::findOrFail($id);
        $ip = $log->ip_address;
        $adminName = Auth::check() ? Auth::user()->name : 'Admin';
        $newStatus = $request->status;

        if ($newStatus === 'safe') {
            // 🟢 Xác nhận an toàn: Gỡ khỏi CSDL Khóa & Cache
            BannedIp::where('ip_address', $ip)->delete();
            Cache::forget('banned_ip_' . $ip);
            Cache::forget('db_banned_ip_' . $ip);

            $log->update([
                'status' => 'safe',
                'banned_until' => null,
            ]);

            return back()->with('success', "Đã xác nhận IP {$ip} AN TOÀN và gỡ khóa thành công!");
        }

        if ($newStatus === 'permanently_banned') {
            // 🔒 Khóa vĩnh viễn
            BannedIp::updateOrCreate(
                ['ip_address' => $ip],
                [
                    'reason' => 'Khóa vĩnh viễn từ Nhật ký IP nghi ngờ (' . $log->reason . ')',
                    'banned_by' => $adminName,
                    'banned_until' => null,
                ]
            );

            Cache::put('banned_ip_' . $ip, true, now()->addYears(10));
            Cache::forget('db_banned_ip_' . $ip);

            $log->update([
                'status' => 'permanently_banned',
                'banned_until' => null,
            ]);

            return back()->with('success', "Đã chuyển IP {$ip} sang KHÓA VĨNH VIỄN!");
        }

        if ($newStatus === '24h_banned') {
            // ⏱️ Khóa lại 24h
            $bannedUntil = now()->addHours(24);

            BannedIp::updateOrCreate(
                ['ip_address' => $ip],
                [
                    'reason' => 'Gia hạn khóa 24h từ Nhật ký IP nghi ngờ (' . $log->reason . ')',
                    'banned_by' => $adminName,
                    'banned_until' => $bannedUntil,
                ]
            );

            Cache::put('banned_ip_' . $ip, true, $bannedUntil);
            Cache::forget('db_banned_ip_' . $ip);

            $log->update([
                'status' => 'auto_banned_24h',
                'banned_until' => $bannedUntil,
            ]);

            return back()->with('success', "Đã gia hạn KHÓA 24 GIỜ cho IP {$ip}!");
        }

        return back();
    }

    public function destroy($id)
    {
        $log = SuspiciousIpLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Đã xóa bản ghi nhật ký khỏi danh sách.');
    }
}
