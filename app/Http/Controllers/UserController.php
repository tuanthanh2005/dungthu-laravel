<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CardExchange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Trang tài khoản
    public function account()
    {
        $user = Auth::user();
        return view('user.account', compact('user'));
    }

    // Cập nhật thông tin tài khoản
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại không được để trống',
            'password.required' => 'Mật khẩu mới không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // Danh sách đơn hàng
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        $cardExchanges = CardExchange::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders', 'cardExchanges'));
    }

    // Chi tiết đơn hàng
    public function orderDetail(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        $order->load('orderItems.product');
        return view('user.order-detail', compact('order'));
    }
}
