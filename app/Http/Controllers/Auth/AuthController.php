<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Hiển thị trang login
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            if (auth()->user()->role === 'blog_editor') {
                return redirect()->intended(route('admin.blogs'))->with('success', 'Chào mừng cộng tác viên Blog!');
            }

            if (in_array(auth()->user()->role, ['superadmin_1', 'sieusuperadmin'], true)) {
                return redirect()->intended('/admin')->with('success', 'Chào mừng Admin!');
            }
            
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email'));
    }

    // Hiển thị trang register
    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // Xử lý register
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
        ]);

        if (config('services.recaptcha.secret_key')) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            $responseData = $response->json();

            if (!($responseData['success'] ?? false) || ($responseData['score'] ?? 0) < 0.5) {
                return back()->withErrors([
                    'email' => 'Hệ thống phát hiện nghi vấn bot. Vui lòng tải lại trang hoặc thử lại.',
                ])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Gửi thông báo qua Telegram
        try {
            \App\Helpers\TelegramHelper::sendNewUserNotification($user);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi thông báo Telegram khi đăng ký: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }
}
