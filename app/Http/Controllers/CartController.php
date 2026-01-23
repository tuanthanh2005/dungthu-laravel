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
            return view('cart.checkout-mixed', compact('cart'));
        } elseif($hasDigital) {
            // Chỉ sản phẩm số/dịch vụ - thanh toán QR
            return view('cart.checkout-digital', compact('cart'));
        } else {
            // Chỉ sản phẩm vật lý - cần địa chỉ giao hàng
            return view('cart.checkout-physical', compact('cart'));
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
            
            // Gửi thông báo Telegram
            TelegramHelper::sendNewOrderNotification($order);
            
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }
    }
}
