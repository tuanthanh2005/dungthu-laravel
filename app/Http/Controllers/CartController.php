<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Helpers\TelegramHelper;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function increment($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, (int) $cart[$id]['quantity'] + 1);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function decrement($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $currentQty = (int) ($cart[$id]['quantity'] ?? 1);
            $newQty = $currentQty - 1;

            if ($newQty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $newQty;
            }

            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('shop')->with('error', 'Giỏ hàng của bạn đang trống!');
        }
        
        // Check if user is logged in and new user (from Google OAuth)
        $isNewUser = auth()->check() && session()->has('is_new_user');
        
        // Phân tích loại sản phẩm trong giỏ hàng
        $hasDigital = false;
        $hasPhysical = false;
        
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            if($product) {
                if($product->delivery_type === 'digital') {
                    $hasDigital = true;
                } else {
                    $hasPhysical = true;
                }
            }
        }
        
        // Chọn view thanh toán phù hợp
        if($hasDigital && $hasPhysical) {
            // Có cả 2 loại
            return view('cart.checkout-mixed', compact('cart', 'isNewUser'));
        } elseif($hasDigital) {
            // Chỉ sản phẩm số/dịch vụ - thanh toán QR
            return view('cart.checkout-digital', compact('cart', 'isNewUser'));
        } else {
            // Chỉ sản phẩm vật lý - cần địa chỉ giao hàng
            return view('cart.checkout-physical', compact('cart', 'isNewUser'));
        }
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart');
        
        // Kiểm tra loại đơn hàng
        $hasPhysical = false;
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            if($product && $product->delivery_type === 'physical') {
                $hasPhysical = true;
                break;
            }
        }
        
        // Validate theo loại đơn hàng
        $rules = [
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
        ];
        
        if($hasPhysical) {
            $rules['customer_address'] = 'required';
        }
        
        $request->validate($rules);

        $totalAmount = 0;
        foreach($cart as $id => $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address ?? 'Sản phẩm số - không cần giao hàng',
                'total_amount' => $totalAmount,
            ]);

            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();
            
            // Clear the "new user" flag after order is placed
            session()->forget('is_new_user');
            
            // Gửi thông báo Telegram cho admin
            TelegramHelper::sendNewOrderNotification($order);
            
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Vui lòng chờ admin xác nhận.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }
    }

    /**
     * Tạo username demo từ thông tin khách hàng
     */
    private function generateDemoUsername(Order $order)
    {
        // Lấy phần trước @ từ email
        if ($order->customer_email) {
            $emailParts = explode('@', $order->customer_email);
            $username = strtolower($emailParts[0]);
            // Thêm số đơn hàng để unique
            return $username . '_demo_' . $order->id;
        }
        
        // Fallback: dùng tên khách hàng
        $name = strtolower(str_replace(' ', '', $order->customer_name));
        return $name . '_demo_' . $order->id;
    }

    /**
     * Generate mật khẩu random mạnh
     */
    private function generateRandomPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*';
        
        $allChars = $uppercase . $lowercase . $numbers . $special;
        $password = '';
        
        // Đảm bảo có ít nhất 1 chữ hoa, 1 chữ thường, 1 số, 1 ký tự đặc biệt
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];
        
        // Tạo phần còn lại
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        
        // Shuffle password để ngẫu nhiên hơn
        $passwordArray = str_split($password);
        shuffle($passwordArray);
        
        return implode('', $passwordArray);
    }
}
