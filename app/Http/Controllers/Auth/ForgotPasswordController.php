<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form for requesting a password reset link (Vô hiệu hóa - chuyển về trang đăng nhập hiển thị modal liên hệ Admin)
     */
    public function showLinkRequestForm()
    {
        return redirect()->route('login', ['forgot' => 1])
            ->with('show_forgot_modal', true);
    }

    /**
     * Send a password reset link to the user (Vô hiệu hóa - chuyển về trang đăng nhập hiển thị modal liên hệ Admin)
     */
    public function sendResetLink(Request $request)
    {
        return redirect()->route('login', ['forgot' => 1])
            ->with('show_forgot_modal', true);
    }
}
