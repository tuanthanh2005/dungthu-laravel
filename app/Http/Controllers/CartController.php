<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Helpers\TelegramHelper;
use App\Models\AbandonedCart;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderApprovedMail;

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
        
        // Bỏ chặn để cho phép đặt hàng khi hết kho
        // if ($product->stock <= 0) {
        //     return redirect()->back()->with('error', 'Sản phẩm này hiện đã hết hàng!');
        // }
        
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->effective_price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        $this->syncAbandonedCart($cart);
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function buyNow($id)
    {
        $product = Product::findOrFail($id);
        
        // Bỏ chặn để cho phép đặt hàng khi hết kho
        // if ($product->stock <= 0) {
        //     return redirect()->back()->with('error', 'Sản phẩm này hiện đã hết hàng!');
        // }
        
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->effective_price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        $this->syncAbandonedCart($cart);

        return redirect()->route('checkout');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        $this->syncAbandonedCart(session()->get('cart', []));
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function increment($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, (int) $cart[$id]['quantity'] + 1);
            session()->put('cart', $cart);
        }

        $this->syncAbandonedCart($cart);
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

        $this->syncAbandonedCart($cart);
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
        
        // Calculate total amount
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Apply coupon if exists in session
        $discountAmount = 0;
        $couponCode = null;
        if (session()->has('applied_coupon')) {
            $couponId = session('applied_coupon');
            $coupon = \App\Models\Coupon::where('id', $couponId)
                ->where(function($query) {
                    $query->where('user_id', auth()->id())
                          ->orWhereNull('user_id');
                })
                ->where('is_used', false)
                ->first();
            if ($coupon) {
                $discountAmount = (float) $coupon->value;
                $couponCode = $coupon->code;
            } else {
                session()->forget('applied_coupon');
            }
        }
        $finalTotal = max(0, $total - $discountAmount);
        
        // Generate a unique order code in session to prevent changes on reload
        if (!session()->has('checkout_order_code')) {
            session()->put('checkout_order_code', 'DT-' . strtoupper(\Illuminate\Support\Str::random(8)));
        }
        $orderCode = session('checkout_order_code');
        
        // Chọn view thanh toán phù hợp
        if($hasDigital && $hasPhysical) {
            // Có cả 2 loại
            return view('cart.checkout-mixed', compact('cart', 'isNewUser', 'discountAmount', 'couponCode', 'finalTotal', 'orderCode'));
        } elseif($hasDigital) {
            // Chỉ sản phẩm số/dịch vụ - thanh toán QR
            return view('cart.checkout-digital', compact('cart', 'isNewUser', 'discountAmount', 'couponCode', 'finalTotal', 'orderCode'));
        } else {
            // Chỉ sản phẩm vật lý - cần địa chỉ giao hàng
            return view('cart.checkout-physical', compact('cart', 'isNewUser', 'discountAmount', 'couponCode', 'finalTotal', 'orderCode'));
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

        $customerAddress = $request->customer_address ?? 'Sản phẩm số - không cần giao hàng';
        if ($request->filled('customer_zalo')) {
            $customerAddress .= "\nZalo: " . $request->customer_zalo;
        }
        if ($request->filled('customer_facebook')) {
            $customerAddress .= "\nFacebook: " . $request->customer_facebook;
        }
        if ($request->filled('payment_method')) {
            $pm = $request->payment_method;
            if ($pm === 'crypto') {
                $customerAddress .= "\nPhương thức thanh toán: Ví Crypto (USDT,...)";
            } elseif ($pm === 'binance_uid') {
                $customerAddress .= "\nPhương thức thanh toán: Binance UID";
            } else {
                $customerAddress .= "\nPhương thức thanh toán: Chuyển khoản VietQR";
            }
        }

        // Kiểm tra xem có sản phẩm nào thiếu kho không
        $hasOutOfStock = false;
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            if(!$product || $product->stock < $details['quantity']) {
                $hasOutOfStock = true;
            }
        }

        $orderStatus = 'pending';

        DB::beginTransaction();
        try {
            // Apply coupon discount if applicable
            $discountAmount = 0;
            $coupon = null;
            if (session()->has('applied_coupon')) {
                $coupon = \App\Models\Coupon::where('id', session('applied_coupon'))
                    ->where(function($query) {
                        $query->where('user_id', auth()->id())
                              ->orWhereNull('user_id');
                    })
                    ->where('is_used', false)
                    ->first();
                if ($coupon) {
                    $discountAmount = (float) $coupon->value;
                }
            }
            $finalTotal = max(0, $totalAmount - $discountAmount);

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $customerAddress,
                'total_amount' => $finalTotal,
                'status' => $orderStatus,
                'coupon_code' => $coupon ? $coupon->code : null,
                'discount_amount' => $discountAmount,
                'order_code' => $request->order_code ?? session('checkout_order_code') ?? ('DT-' . strtoupper(\Illuminate\Support\Str::random(8))),
            ]);

            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // Trừ tồn kho nếu sản phẩm còn hàng sẵn
                $product = Product::find($id);
                if ($product && $product->stock >= $details['quantity']) {
                    $product->decrement('stock', $details['quantity']);
                }
            }

            // Mark coupon as used
            if ($coupon) {
                $coupon->update([
                    'user_id' => auth()->id(),
                    'is_used' => true,
                    'used_at' => now(),
                    'order_id' => $order->id,
                ]);
                session()->forget('applied_coupon');
            }

            DB::commit();
            
            // Clear the "new user" flag after order is placed
            session()->forget('is_new_user');
            session()->forget('checkout_order_code');
            
            // Gửi email duyệt đơn tự động nếu đơn hàng thành công (completed)
            if ($orderStatus === 'completed') {
                try {
                    $email = $order->customer_email;
                    if (!$email && $order->user_id) {
                        $email = optional($order->user)->email;
                    }
                    if ($email) {
                        $order->load('orderItems.product');
                        Mail::to($email)->send(new OrderApprovedMail($order));
                        \Log::info('Auto-approved order email sent to ' . $email . ' for order #' . $order->id);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error sending auto-approved order email for order #' . $order->id . ': ' . $e->getMessage());
                }
            }
            
            // Gửi thông báo Telegram cho admin
            TelegramHelper::sendNewOrderNotification($order);
            
            $this->clearAbandonedCart();
            session()->forget('cart');
            
            if ($orderStatus === 'completed') {
                return redirect()->route('user.orders')->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đã hoàn thành.');
            } else {
                return redirect()->route('user.orders')->with('success', 'Đặt hàng thành công! Vui lòng chờ admin xác nhận.');
            }

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
        $numbers = '0772698113';
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

    private function syncAbandonedCart(array $cart): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();
        $email = $user?->email;
        if (!$email) {
            return;
        }

        if (empty($cart)) {
            AbandonedCart::where('user_id', $user->id)->delete();
            return;
        }

        $itemsCount = 0;
        $totalAmount = 0;
        foreach ($cart as $item) {
            $qty = (int) ($item['quantity'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $itemsCount += $qty;
            $totalAmount += ($price * $qty);
        }

        AbandonedCart::updateOrCreate(
            ['user_id' => $user->id],
            [
                'email' => $email,
                'cart_data' => $cart,
                'items_count' => $itemsCount,
                'total_amount' => $totalAmount,
                'last_activity_at' => now(),
                'reminder_stage' => 0,
                'last_reminder_at' => null,
            ]
        );
    }

    private function clearAbandonedCart(): void
    {
        if (!auth()->check()) {
            return;
        }

        AbandonedCart::where('user_id', auth()->id())->delete();
    }

    /**
     * Apply coupon code in session.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);

        $coupon = \App\Models\Coupon::where('code', $code)
            ->where(function($query) {
                $query->where('user_id', auth()->id())
                      ->orWhereNull('user_id');
            })
            ->where('is_used', false)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã được sử dụng!'
            ], 422);
        }

        // Calculate current total
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Put in session
        session()->put('applied_coupon', $coupon->id);

        $discountAmount = (float) $coupon->value;
        $finalTotal = max(0, $total - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'coupon_code' => $coupon->code,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'final_total' => $finalTotal,
        ]);
    }

    /**
     * Remove coupon code from session.
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy áp dụng mã giảm giá!',
            'total' => $total,
            'final_total' => $total,
        ]);
    }
}


