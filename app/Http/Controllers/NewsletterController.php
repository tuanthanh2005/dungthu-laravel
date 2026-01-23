<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được đăng ký',
        ]);

        try {
            Newsletter::create(['email' => $validated['email']]);
            
            return response()->json([
                'success' => true,
                'message' => '✅ Đăng ký thành công! Cảm ơn bạn.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
