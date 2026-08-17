<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannedIp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class BannedIpController extends Controller
{
    public function index(Request $request)
    {
        $query = BannedIp::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('reason', 'LIKE', "%{$search}%");
        }

        $bannedIps = $query->latest()->paginate(20)->withQueryString();

        return view('admin.banned_ips.index', compact('bannedIps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string|ip',
            'reason' => 'nullable|string|max:255',
            'duration' => 'nullable|string|in:permanent,1_day,7_days,30_days',
        ], [
            'ip_address.required' => 'Vui lòng nhập địa chỉ IP',
            'ip_address.ip' => 'Địa chỉ IP không đúng định dạng (Ví dụ: 116.96.77.80)',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự',
        ]);

        $ip = trim($request->ip_address);
        $duration = $request->input('duration', 'permanent');

        $bannedUntil = null;
        if ($duration === '1_day') {
            $bannedUntil = now()->addDay();
        } elseif ($duration === '7_days') {
            $bannedUntil = now()->addDays(7);
        } elseif ($duration === '30_days') {
            $bannedUntil = now()->addDays(30);
        }

        $bannedBy = Auth::check() ? Auth::user()->name : 'Admin';

        BannedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $request->reason ?: 'Chặn thủ công từ trang quản trị',
                'banned_by' => $bannedBy,
                'banned_until' => $bannedUntil,
            ]
        );

        // Lưu vào Cache ngay lập tức để ngắt kết nối 100% không cần truy vấn DB
        if ($bannedUntil) {
            Cache::put('banned_ip_' . $ip, true, $bannedUntil);
        } else {
            Cache::put('banned_ip_' . $ip, true, now()->addYears(10));
        }
        Cache::forget('db_banned_ip_' . $ip);

        // Gửi thông báo Telegram
        $reasonText = $request->reason ?: 'Chặn thủ công từ trang quản trị';
        \App\Helpers\TelegramHelper::sendSuspiciousIpNotification(
            $ip,
            "Đã bị Admin ({$bannedBy}) khóa thủ công qua trang Quản lý Khóa IP. (Lý do: {$reasonText})"
        );

        return back()->with('success', "Đã khóa thành công IP: {$ip}");
    }

    public function destroy($id)
    {
        $bannedIp = BannedIp::findOrFail($id);
        $ip = $bannedIp->ip_address;

        $bannedIp->delete();

        // Xóa khỏi Cache để IP có thể truy cập trở lại ngay lập tức
        Cache::forget('banned_ip_' . $ip);
        Cache::forget('db_banned_ip_' . $ip);

        return back()->with('success', "Đã gỡ bỏ khóa cho IP: {$ip}");
    }
}
