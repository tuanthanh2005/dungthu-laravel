<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRouteLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Các route được miễn trừ (Chỉ trang xác thực)
        $allowedRoutes = [
            'admin.verify-pin',
            'admin.verify-pin.post'
        ];

        $currentRoute = $request->route()->getName();

        // Kiểm tra xem session đã unlock chưa và mã băm mật khẩu hiện tại có khớp với cấu hình .env không
        $expectedHash = md5((string) config('admin.gate_password'));
        $isUnlocked = session('admin_unlocked') === true && session('admin_unlocked_hash') === $expectedHash;

        if ($isUnlocked || in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // Nếu chưa mở khóa, xóa session cũ và chuyển hướng đến trang xác thực
        session()->forget(['admin_unlocked', 'admin_unlocked_hash']);

        if ($request->is('admin/*') || $request->is('admin')) {
            return redirect()->route('admin.verify-pin')->with('target_url', $request->fullUrl());
        }

        return $next($request);
    }
}
