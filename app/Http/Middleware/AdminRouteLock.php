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
        // Các route được miễn trừ (Dashboard, Blog, và trang xác thực)
        $allowedRoutes = [
            'admin.dashboard',
            'admin.blogs',
            'admin.blogs.create',
            'admin.blogs.store',
            'admin.blogs.edit',
            'admin.blogs.update',
            'admin.blogs.delete',
            'admin.verify-pin',
            'admin.verify-pin.post'
        ];

        $currentRoute = $request->route()->getName();

        // Nếu đã unlock trong session hoặc đang truy cập route được cho phép
        if (session('admin_unlocked') === true || in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // Nếu truy cập các route admin khác mà chưa unlock
        if ($request->is('admin/*') || $request->is('admin')) {
            return redirect()->route('admin.verify-pin')->with('target_url', $request->fullUrl());
        }

        return $next($request);
    }
}
