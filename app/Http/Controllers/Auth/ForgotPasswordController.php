<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form for requesting a password reset link
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the user
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        $ipKey = 'forgot_password_ip:' . $request->ip();
        $email = Str::lower(trim((string) $request->input('email')));
        $emailKey = 'forgot_password_email:' . md5($email);
        $decaySeconds = 3600; // Khóa 1 tiếng (3600 giây)
        $maxAttempts = 5;     // Cho phép tối đa 5 lần

        // Nếu đã bấm 5 lần rồi mà cố tình bấm lần 6 -> Chặn chờ 1 tiếng
        if (RateLimiter::tooManyAttempts($ipKey, $maxAttempts) || RateLimiter::tooManyAttempts($emailKey, $maxAttempts)) {
            $seconds = max(
                RateLimiter::availableIn($ipKey),
                RateLimiter::availableIn($emailKey)
            );
            $minutes = max(1, (int) ceil($seconds / 60));
            $message = __('Bạn đã yêu cầu đặt lại mật khẩu quá 5 lần. Vui lòng chờ :minutes phút (1 tiếng) trước khi thử lại.', ['minutes' => $minutes]);

            return back()->withErrors(['email' => $message])->with('error', $message)->withInput();
        }

        // Ghi nhận lượt bấm quên mật khẩu (lưu hạn 1 tiếng)
        RateLimiter::hit($ipKey, $decaySeconds);
        RateLimiter::hit($emailKey, $decaySeconds);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.'])->withInput();
        }

        // Send password reset link using Laravel's built-in function
        $status = Password::sendResetLink(
            $request->only('email')
        );

        Log::info('Password reset link sent', [
            'email' => $request->email,
            'status' => $status
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Email hướng dẫn đặt lại mật khẩu đã được gửi đến ' . $request->email . '. Vui lòng kiểm tra email của bạn (bao gồm thư mục Spam)!');
        }

        return back()->withErrors(['email' => 'Không thể gửi email reset link.'])->withInput();
    }
}
