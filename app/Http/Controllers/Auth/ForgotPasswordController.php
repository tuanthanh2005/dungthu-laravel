<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

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
