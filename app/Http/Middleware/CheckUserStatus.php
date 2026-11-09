<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $isAdmin = in_array($user->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true);
            $email = strtolower(trim($user->email));
            $isNotGmail = !str_ends_with($email, '@gmail.com');

            // Kiểm tra nếu tài khoản không phải đuôi @gmail.com (không phải admin) hoặc tài khoản đã bị khóa
            if (($isNotGmail && !$isAdmin) || $user->is_locked) {
                if ($isNotGmail && !$isAdmin && !$user->is_locked) {
                    $user->update([
                        'is_locked' => true,
                        'locked_reason' => 'Email không có đuôi @gmail.com (Nghi ngờ vi phạm)',
                    ]);
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = 'Tài khoản của bạn nghi ngờ vi phạm, xin hãy đăng nhập tài khoản khác.';

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'locked',
                        'message' => $message,
                    ], 403);
                }

                return redirect()->route('login')->with([
                    'show_banned_modal' => true,
                    'banned_modal_message' => $message,
                ]);
            }
        }

        return $next($request);
    }
}
