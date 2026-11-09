<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\RegisterVerificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        // Kiểm tra xem tài khoản có tồn tại không
        if ($user) {
            $isAdmin = in_array($user->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true);
            $isNotGmail = !str_ends_with($email, '@gmail.com');

            // Nếu tài khoản thông thường không phải @gmail.com -> Tự động khóa và chặn
            if ($isNotGmail && !$isAdmin) {
                $user->update([
                    'is_locked' => true,
                    'locked_reason' => 'Email không có đuôi @gmail.com (Nghi ngờ vi phạm)',
                ]);
            }

            // Nếu tài khoản đã bị khóa
            if ($user->is_locked) {
                return redirect()->route('login')->with([
                    'show_banned_modal' => true,
                    'banned_modal_message' => 'Tài khoản của bạn nghi ngờ vi phạm, xin hãy đăng nhập tài khoản khác.',
                ])->withInput($request->only('email'));
            }
        }

        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $currentUser = Auth::user();
            $isAdmin = in_array($currentUser->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true);
            $isNotGmail = !str_ends_with(strtolower(trim($currentUser->email)), '@gmail.com');

            // Hậu kiểm tra đăng nhập
            if (($isNotGmail && !$isAdmin) || $currentUser->is_locked) {
                if ($isNotGmail && !$isAdmin && !$currentUser->is_locked) {
                    $currentUser->update([
                        'is_locked' => true,
                        'locked_reason' => 'Email không có đuôi @gmail.com (Nghi ngờ vi phạm)',
                    ]);
                }
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with([
                    'show_banned_modal' => true,
                    'banned_modal_message' => 'Tài khoản của bạn nghi ngờ vi phạm, xin hãy đăng nhập tài khoản khác.',
                ]);
            }

            $request->session()->regenerate();
            $request->session()->forget('guest_first_seen_at');
            cookie()->queue(cookie()->forget('guest_first_seen_at'));
            
            if ($currentUser->role === 'blog_editor') {
                return redirect()->intended(route('admin.blogs'))->with('success', 'Chào mừng cộng tác viên Blog!');
            }

            if (in_array($currentUser->role, ['superadmin_1', 'sieusuperadmin'], true)) {
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

    /**
     * Gửi mã OTP xác thực email đăng ký
     */
    public function sendRegisterOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        $email = strtolower(trim($request->email));

        // 1. Chỉ chấp nhận đuôi @gmail.com
        if (!str_ends_with($email, '@gmail.com')) {
            return response()->json([
                'success' => false,
                'message' => 'Hệ thống chỉ chấp nhận đăng ký bằng tài khoản có đuôi @gmail.com.',
            ], 422);
        }

        // 2. Kiểm tra xem email đã được đăng ký chưa
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email này đã được sử dụng. Vui lòng đăng nhập hoặc dùng email khác.',
            ], 422);
        }

        // 3. Giới hạn tần suất gửi: 60s cooldown per email
        $recentOtp = EmailVerification::where('email', $email)
            ->where('created_at', '>', now()->subSeconds(60))
            ->first();
        if ($recentOtp) {
            $remainingSeconds = max(1, 60 - now()->diffInSeconds($recentOtp->created_at));
            return response()->json([
                'success' => false,
                'message' => "Vui lòng đợi {$remainingSeconds} giây trước khi yêu cầu gửi lại mã.",
            ], 429);
        }

        // 4. Giới hạn theo IP (tối đa 10 lần gửi trong 15 phút)
        $ipCount = EmailVerification::where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subMinutes(15))
            ->count();
        if ($ipCount >= 10) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi yêu cầu quá nhiều lần. Vui lòng thử lại sau 15 phút.',
            ], 429);
        }

        // 5. Tạo mã 6 chữ số
        $code = (string) random_int(100000, 999999);

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'ip_address' => $request->ip(),
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        // 6. Gửi email
        try {
            Mail::to($email)->send(new RegisterVerificationMail($code));
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi email xác thực đăng ký: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email xác thực. Vui lòng kiểm tra lại địa chỉ email hoặc thử lại sau.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Mã xác thực đã được gửi đến {$email}. Vui lòng kiểm tra hộp thư đến (và mục Spam/Rác)!",
        ]);
    }

    // Xử lý register
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'verification_code' => 'required|string|size:6',
            'captcha' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($rules, [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng để đăng ký tài khoản.',
            'verification_code.required' => 'Vui lòng nhập mã xác thực email gồm 6 chữ số.',
            'verification_code.size' => 'Mã xác thực email phải gồm đúng 6 chữ số.',
            'captcha.required' => 'Vui lòng nhập mã captcha bảo vệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
        ]);

        $email = strtolower(trim($request->email));

        // 1. Bắt buộc đuôi @gmail.com
        if (!str_ends_with($email, '@gmail.com')) {
            return back()->withErrors([
                'email' => 'Hệ thống chỉ chấp nhận đăng ký bằng tài khoản có đuôi @gmail.com.',
            ])->withInput($request->except('password', 'password_confirmation', 'captcha'));
        }

        // 2. Xác thực Captcha nhập vào
        $sessionCaptcha = session('register_captcha');
        if (empty($sessionCaptcha) || strtolower(trim($request->input('captcha'))) !== $sessionCaptcha) {
            return back()->withErrors([
                'captcha' => 'Mã captcha bảo vệ không chính xác hoặc đã hết hạn. Vui lòng thử lại.',
            ])->withInput($request->except('password', 'password_confirmation', 'captcha'));
        }

        // 3. Xác thực mã số OTP gửi qua email
        $code = trim($request->input('verification_code'));
        $verification = EmailVerification::where('email', $email)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors([
                'verification_code' => 'Mã xác thực email không đúng hoặc đã hết hạn (10 phút). Vui lòng nhấn gửi lại mã mới.',
            ])->withInput($request->except('password', 'password_confirmation', 'captcha'));
        }

        // 4. reCAPTCHA v3 nếu có cấu hình
        if (config('services.recaptcha.secret_key')) {
            try {
                $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);

                $responseData = $response->json();

                if (!($responseData['success'] ?? false) || ($responseData['score'] ?? 0) < 0.5) {
                    return back()->withErrors([
                        'email' => 'Hệ thống phát hiện nghi vấn bot. Vui lòng tải lại trang hoặc thử lại.',
                    ])->withInput($request->except('password', 'password_confirmation', 'captcha'));
                }
            } catch (\Exception $e) {
                Log::warning('reCAPTCHA siteverify error/timeout: ' . $e->getMessage());
            }
        }

        // Hợp lệ -> Xóa mã OTP và captcha đã dùng
        $verification->delete();
        $request->session()->forget('register_captcha');

        // Tạo tài khoản người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_locked' => false,
            'email_verified_at' => now(),
        ]);

        // Gửi thông báo qua Telegram
        try {
            \App\Helpers\TelegramHelper::sendNewUserNotification($user);
        } catch (\Exception $e) {
            Log::error('Lỗi thông báo Telegram khi đăng ký: ' . $e->getMessage());
        }

        Auth::login($user);
        $request->session()->forget('guest_first_seen_at');
        cookie()->queue(cookie()->forget('guest_first_seen_at'));

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        cookie()->queue(cookie()->forget('guest_first_seen_at'));

        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }
}
