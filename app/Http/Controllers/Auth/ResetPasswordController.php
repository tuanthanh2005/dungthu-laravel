<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form (Vô hiệu hóa - chuyển về trang đăng nhập hiển thị modal liên hệ Admin)
     */
    public function showResetForm(Request $request, $token = null)
    {
        return redirect()->route('login', ['forgot' => 1])
            ->with('show_forgot_modal', true);
    }

    /**
     * Reset the given user's password (Vô hiệu hóa - chuyển về trang đăng nhập hiển thị modal liên hệ Admin)
     */
    public function reset(Request $request)
    {
        return redirect()->route('login', ['forgot' => 1])
            ->with('show_forgot_modal', true);
    }
}
