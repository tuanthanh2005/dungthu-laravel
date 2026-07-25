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
        if (!auth()->check() || !in_array(auth()->user()->role, ['superadmin_1', 'sieusuperadmin'])) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }

        $user = auth()->user();
        $method = strtoupper($request->getMethod());
        $routeName = $request->route() ? $request->route()->getName() : null;

        // Ghi log debug phân quyền
        \Illuminate\Support\Facades\Log::info("DEBUG AUTH: User ID: {$user->id}, Role: {$user->role}, Route: {$routeName}, URL: " . $request->fullUrl());

        // 1. Phân quyền truy cập cho SieuSuperAdmin
        if ($user->role === 'sieusuperadmin' && $routeName) {
            $allowedRoutePatterns = [
                'admin.dashboard',
                'admin.verify-pin',
                'admin.verify-pin.post',
                'admin.lock',
                'admin.sidebar-counters',
                'admin.orders*',
                'admin.chat*',
                'admin.products*',
                'admin.categories*',
                'admin.features*',
                'admin.customer-durations*',
                'admin.blogs*',
                'admin.blog-topics*',
                'admin.users*',
                'admin.coupons*',
                'admin.menu-settings*',
                'admin.google-indexing.submit-all',
            ];

            $isAllowed = false;
            foreach ($allowedRoutePatterns as $pattern) {
                if (\Illuminate\Support\Str::is($pattern, $routeName)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                \Illuminate\Support\Facades\Log::warning("Unauthorized admin access attempt by SieuSuperAdmin (User ID: {$user->id}) to route: {$routeName} from IP: {$request->ip()}");
                abort(403, "Tài khoản của bạn không được cấp quyền truy cập tính năng này. (Role: {$user->role}, Route: {$routeName})");
            }

            // Ghi nhật ký các hành động thay đổi dữ liệu nhạy cảm
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                \Illuminate\Support\Facades\Log::info("SieuSuperAdmin Action: User ID {$user->id} performed {$method} on URL {$request->fullUrl()} from IP: {$request->ip()}", [
                    'input' => $request->except(['gate_password', 'password', 'password_confirmation', '_token']),
                ]);
            }
        }

        // 2. Phân quyền chặn Superadmin thường (superadmin_1) truy cập các trang độc quyền của SieuSuperAdmin
        if ($user->role === 'superadmin_1' && $routeName) {
            $restrictedRoutePatterns = [
                'admin.orders*',
                'admin.chat*',
                'admin.products*',
                'admin.categories*',
                'admin.features*',
                'admin.customer-durations*',
                'admin.blogs*',
                'admin.blog-topics*',
                'admin.users*',
                'admin.coupons*',
                'admin.menu-settings*',
            ];

            foreach ($restrictedRoutePatterns as $pattern) {
                if (\Illuminate\Support\Str::is($pattern, $routeName)) {
                    \Illuminate\Support\Facades\Log::warning("Unauthorized admin access attempt by superadmin_1 (User ID: {$user->id}) to sieusuperadmin route: {$routeName} from IP: {$request->ip()}");
                    abort(403, "Quyền hạn của bạn không đủ để truy cập khu vực này. (Role: {$user->role}, Route: {$routeName})");
                }
            }
        }

        // 2. Bỏ qua nhập mã PIN nếu đã mở khóa Gate bảo mật
        if (session('admin_unlocked') === true) {
            return $next($request);
        }

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
