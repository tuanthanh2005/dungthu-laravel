<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminPin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Nếu đã "mở khóa" trong session (qua AdminRouteLock) thì cho phép mọi thao tác
        if (session('admin_unlocked') === true) {
            return $next($request);
        }

        // 2. Các phương thức GET/HEAD/OPTIONS luôn được cho phép
        $method = strtoupper($request->getMethod());
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        // 3. Kiểm tra danh sách route được miễn nhập mã PIN từ config
        $routeName = $request->route()?->getName();
        $exemptRoutes = config('admin.pin_exempt_route_names', []);
        if ($routeName && in_array($routeName, $exemptRoutes)) {
            return $next($request);
        }

        // 4. Nếu chưa mở khóa và không được miễn, kiểm tra PIN trong request input
        // Điều này hỗ trợ trường hợp muốn bảo mật riêng từng tác vụ quan trọng
        $pin = $request->input('admin_pin');
        if (!is_string($pin) || !preg_match('/^\d{3}$/', $pin)) {
            return $this->deny($request, 'Vui lòng nhập mã xác nhận đúng 3 số.');
        }

        $expected = (string) config('admin.action_pin', '999');
        if ($pin !== $expected) {
            return $this->deny($request, 'Sai mã xác nhận. Vui lòng thử lại.');
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

