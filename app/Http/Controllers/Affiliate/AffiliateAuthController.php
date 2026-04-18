<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('affiliate')->check()) {
            return redirect()->route('affiliate.dashboard');
        }
        return view('affiliate.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('affiliate')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('affiliate.dashboard');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::guard('affiliate')->check()) {
            return redirect()->route('affiliate.dashboard');
        }
        return view('affiliate.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:affiliates,email',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:500',
            'cccd_number'=> 'required|string|max:20',
            'cccd_front' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'cccd_back'  => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'name.required'       => 'Họ tên không được để trống.',
            'email.required'      => 'Email không được để trống.',
            'email.unique'        => 'Email này đã được đăng ký.',
            'password.min'        => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
            'phone.required'      => 'Số điện thoại không được để trống.',
            'address.required'    => 'Địa chỉ không được để trống.',
            'cccd_number.required'=> 'Số CCCD không được để trống.',
            'cccd_front.required' => 'Vui lòng upload ảnh CCCD mặt trước.',
            'cccd_back.required'  => 'Vui lòng upload ảnh CCCD mặt sau.',
            'cccd_front.image'    => 'CCCD mặt trước phải là ảnh.',
            'cccd_back.image'     => 'CCCD mặt sau phải là ảnh.',
            'cccd_front.max'      => 'CCCD mặt trước không được quá 5MB.',
            'cccd_back.max'       => 'CCCD mặt sau không được quá 5MB.',
        ]);

        // Upload CCCD images
        $cccdFrontPath = null;
        $cccdBackPath = null;

        if ($request->hasFile('cccd_front')) {
            $file = $request->file('cccd_front');
            $filename = time() . '_cccd_front_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/cccd'), $filename);
            $cccdFrontPath = 'images/cccd/' . $filename;
        }

        if ($request->hasFile('cccd_back')) {
            $file = $request->file('cccd_back');
            $filename = time() . '_cccd_back_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/cccd'), $filename);
            $cccdBackPath = 'images/cccd/' . $filename;
        }

        $affiliate = Affiliate::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone'        => $request->phone,
            'address'      => $request->address,
            'cccd_number'  => $request->cccd_number,
            'cccd_front'   => $cccdFrontPath,
            'cccd_back'    => $cccdBackPath,
            'bank_name'    => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $request->bank_account_name,
            'status'       => 'pending',
            'referral_code'=> strtoupper(Str::random(8)),
        ]);

        // Notify admin via Telegram
        $msg  = "👤 <b>ĐĂNG KÝ CỘNG TÁC VIÊN MỚI</b>\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $msg .= "• Họ tên: <b>{$affiliate->name}</b>\n";
        $msg .= "• Email: <b>{$affiliate->email}</b>\n";
        $msg .= "• SĐT: <b>{$affiliate->phone}</b>\n";
        $msg .= "• CCCD: <b>{$affiliate->cccd_number}</b>\n";
        $msg .= "• Ngày đăng ký: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";
        $msg .= "⚠️ <i>Vui lòng vào Admin để duyệt hồ sơ!</i>\n";
        $msg .= "🔗 " . url('/admin/affiliates');
        TelegramHelper::sendMessage($msg);

        // Log the affiliate in automatically
        Auth::guard('affiliate')->login($affiliate);
        $request->session()->regenerate();

        return redirect()->route('affiliate.pending')->with('success', 'Đăng ký thành công! Vui lòng chờ admin duyệt hồ sơ của bạn.');
    }

    public function logout(Request $request)
    {
        Auth::guard('affiliate')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('affiliate.login')->with('success', 'Đã đăng xuất thành công.');
    }
}
