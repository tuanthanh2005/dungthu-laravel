<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminPin
{
    public function handle(Request $request, Closure $next): Response
    {
        $method = strtoupper($request->getMethod());
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

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

