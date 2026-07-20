<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin_1') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }

        // Require 8-digit PIN for admin write actions (POST/PUT/PATCH/DELETE)
        $method = strtoupper($request->getMethod());
        
        // Block all DELETE requests temporarily for security
        if ($method === 'DELETE') {
            return $this->deny($request, 'Chức năng xóa dữ liệu tạm thời bị khóa vì lý do bảo mật!');
        }

        if (!in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            $pin = $request->input('admin_pin');
            if (!is_string($pin) || !preg_match('/^\d{8}$/', $pin)) {
                return $this->deny($request, 'Vui lòng nhập mã xác nhận đúng 8 số.');
            }

            $expected = (string) config('admin.action_pin', '12112004');
            if ($pin !== $expected) {
                return $this->deny($request, 'Sai mã xác nhận. Vui lòng thử lại.');
            }
        }
        
        return $next($request);
    }

    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return back()
            ->with('error', $message)
            ->withInput();
    }
}
