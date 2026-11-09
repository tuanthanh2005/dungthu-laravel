<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Helpers\TelegramHelper;
use App\Models\AbandonedCart;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderApprovedMail;
use App\Mail\PreCheckoutAdminMail;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $adjusted = false;
        $messages = [];

        foreach ($cart as $id => $details) {
            $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
            $variantId = $details['variant_id'] ?? null;
            $product = Product::find($productId);

            if (!$product) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm không tồn tại đã được xóa khỏi giỏ hàng.";
                continue;
            }

            if ($product->hasVariants() && !$variantId) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm '{$product->name}' có nhiều gói dịch vụ, vui lòng vào trang chi tiết để chọn gói phù hợp.";
                continue;
            }

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant || !$variant->is_active) {
                    unset($cart[$id]);
                    $adjusted = true;
                    $messages[] = "Gói dịch vụ không còn khả dụng và đã được xóa khỏi giỏ hàng.";
                    continue;
                }
                if ($variant->stock <= 0) {
                    unset($cart[$id]);
                    $adjusted = true;
                    $messages[] = "Gói dịch vụ '{$variant->name}' đã hết hàng và được xóa khỏi giỏ hàng.";
                    continue;
                }
            } elseif ($product->stock <= 0) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm '{$product->name}' đã hết hàng và được xóa khỏi giỏ hàng.";
                continue;
            }
        }

        if ($adjusted) {
            session()->put('cart', $cart);
            $this->syncAbandonedCart($cart);
            if (!empty($messages)) {
                session()->flash('error', implode(' ', $messages));
            }
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $variantId = $request->input('variant_id');
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->where('is_active', true)->findOrFail($variantId);
            if ($variant->stock <= 0) {
                return redirect()->back()->with('error', 'Gói dịch vụ "' . $variant->name . '" hiện đã hết hàng!');
            }
        } elseif ($product->hasVariants()) {
            return redirect()->route('product.show', $product->slug)
                ->with('error', 'Sản phẩm có nhiều gói dịch vụ, vui lòng vào trang chi tiết để chọn gói phù hợp!');
        } elseif ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm này hiện đã hết hàng!');
        }

        $cart = session()->get('cart', []);
        $cartKey = $variant ? ($product->id . '_' . $variant->id) : (string) $product->id;
        $price = $variant ? (float) $variant->effective_price : (float) $product->effective_price;
        $name = $variant ? ($product->name . ' (' . $variant->name . ')') : $product->name;
        $maxStock = $variant ? $variant->stock : $product->stock;

        if (isset($cart[$cartKey])) {
            if ($cart[$cartKey]['quantity'] >= $maxStock) {
                return redirect()->back()->with('error', 'Không thể thêm vì đã đạt giới hạn tồn kho!');
            }
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "variant_id" => $variant ? $variant->id : null,
                "variant_name" => $variant ? $variant->name : null,
                "name" => $name,
                "base_name" => $product->name,
                "slug" => $product->slug,
                "quantity" => 1,
                "price" => $price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        $this->syncAbandonedCart($cart);
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function buyNow(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $variantId = $request->input('variant_id');
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->where('is_active', true)->findOrFail($variantId);
            if ($variant->stock <= 0) {
                return redirect()->back()->with('error', 'Gói dịch vụ "' . $variant->name . '" hiện đã hết hàng!');
            }
        } elseif ($product->hasVariants()) {
            return redirect()->route('product.show', $product->slug)
                ->with('error', 'Sản phẩm có nhiều gói dịch vụ, vui lòng vào trang chi tiết để chọn gói phù hợp!');
        } elseif ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm này hiện đã hết hàng!');
        }

        $cart = session()->get('cart', []);
        $cartKey = $variant ? ($product->id . '_' . $variant->id) : (string) $product->id;
        $price = $variant ? (float) $variant->effective_price : (float) $product->effective_price;
        $name = $variant ? ($product->name . ' (' . $variant->name . ')') : $product->name;
        $maxStock = $variant ? $variant->stock : $product->stock;

        if (isset($cart[$cartKey])) {
            if ($cart[$cartKey]['quantity'] < $maxStock) {
                $cart[$cartKey]['quantity']++;
            }
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "variant_id" => $variant ? $variant->id : null,
                "variant_name" => $variant ? $variant->name : null,
                "name" => $name,
                "base_name" => $product->name,
                "slug" => $product->slug,
                "quantity" => 1,
                "price" => $price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

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
            $item = $cart[$id];
            $variantId = $item['variant_id'] ?? null;
            $productId = $item['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);

            $maxStock = 0;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                $maxStock = $variant ? $variant->stock : 0;
            } else {
                $product = Product::find($productId);
                $maxStock = $product ? $product->stock : 0;
            }

            if ($cart[$id]['quantity'] >= $maxStock) {
                return redirect()->back()->with('error', 'Không thể tăng thêm số lượng vì đã đạt giới hạn tồn kho!');
            }
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

        // The previous implementation called Product::find() once for every
        // cart item in three separate loops. Fetch the current product state
        // once so checkout remains fast even with a large saved cart.
        $productIds = array_map('intval', array_keys($cart));
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
        
        // Kiểm tra tồn kho của sản phẩm/gói dịch vụ trong giỏ hàng
        $adjusted = false;
        $messages = [];
        foreach($cart as $id => $details) {
            $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
            $variantId = $details['variant_id'] ?? null;
            $product = Product::find($productId);

            if (!$product) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm không tồn tại đã được xóa khỏi giỏ hàng.";
                continue;
            }

            $currentStock = $product->stock;
            $itemName = $product->name;

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant || !$variant->is_active) {
                    unset($cart[$id]);
                    $adjusted = true;
                    $messages[] = "Gói dịch vụ không còn khả dụng và đã được xóa khỏi giỏ hàng.";
                    continue;
                }
                $currentStock = $variant->stock;
                $itemName = $product->name . ' (' . $variant->name . ')';
            } elseif ($product->hasVariants()) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm '{$product->name}' yêu cầu chọn gói dịch vụ cụ thể và đã được xóa khỏi giỏ hàng.";
                continue;
            }

            if ($currentStock <= 0) {
                unset($cart[$id]);
                $adjusted = true;
                $messages[] = "Sản phẩm '{$itemName}' đã hết hàng và được xóa khỏi giỏ hàng.";
            } elseif ($details['quantity'] > $currentStock) {
                $cart[$id]['quantity'] = $currentStock;
                $adjusted = true;
                $messages[] = "Số lượng sản phẩm '{$itemName}' được điều chỉnh về {$currentStock} do vượt quá tồn kho.";
            }
        }
        
        if ($adjusted) {
            session()->put('cart', $cart);
            $this->syncAbandonedCart($cart);
            return redirect()->route('cart.index')->with('error', implode(' ', $messages));
        }
        
        // Check if user is logged in and new user (from Google OAuth)
        $isNewUser = auth()->check() && session()->has('is_new_user');
        
        // Phân tích loại sản phẩm trong giỏ hàng
        $hasDigital = false;
        $hasPhysical = false;
        
        foreach($cart as $id => $details) {
            $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
            $product = Product::find($productId);
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
            $price = (float) $details['price'];
            $total += $price * $details['quantity'];
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
                if (app()->getLocale() === 'en') {
                    $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                    $discountAmount = $discountAmount / $rate;
                }
                $couponCode = $coupon->code;
            } else {
                session()->forget('applied_coupon');
            }
        }
        $finalTotal = max(0, $total - $discountAmount);
        
        // Generate a unique order code in session to prevent changes on reload
        $now = now()->timestamp;
        if (!session()->has('checkout_order_code') || !session()->has('checkout_order_time') || ($now - session('checkout_order_time') >= 300)) {
            session()->put('checkout_order_code', 'DT-' . strtoupper(\Illuminate\Support\Str::random(8)));
            session()->put('checkout_order_time', $now);
        }
        $orderCode = session('checkout_order_code');

        // Kiểm tra giới hạn hủy đơn (tối đa 3 lần) và giới hạn đơn chờ (tối đa 2 đơn)
        $user = auth()->user();
        $isAdmin = $user && (in_array($user->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true) || (bool) $user->is_admin);

        $cancelledOrdersCount = 0;
        $pendingOrdersCount = 0;

        if ($user && !$isAdmin) {
            $cancelledOrdersCount = Order::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })->where('status', 'cancelled')->count();

            $pendingOrdersCount = Order::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })->whereIn('status', ['pending', 'processing'])->count();

            if ($cancelledOrdersCount >= 3) {
                session()->flash('show_cancelled_limit_modal', true);
            } elseif ($pendingOrdersCount >= 2) {
                session()->flash('show_pending_limit_modal', true);
            }
        }
        
        // Chọn view thanh toán phù hợp
        $viewData = compact('cart', 'isNewUser', 'discountAmount', 'couponCode', 'finalTotal', 'orderCode', 'cancelledOrdersCount', 'pendingOrdersCount');
        if($hasDigital && $hasPhysical) {
            // Có cả 2 loại
            return view('cart.checkout-mixed', $viewData);
        } elseif($hasDigital) {
            // Chỉ sản phẩm số/dịch vụ - thanh toán QR
            return view('cart.checkout-digital', $viewData);
        } else {
            // Chỉ sản phẩm vật lý - cần địa chỉ giao hàng
            return view('cart.checkout-physical', $viewData);
        }
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart');
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Kiểm tra tài khoản người dùng: Nếu bị khóa hoặc email không phải @gmail.com thì chặn ngay
        if (auth()->check()) {
            $currentUser = auth()->user();
            $isAdmin = in_array($currentUser->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true) || (bool) $currentUser->is_admin;

            if ($currentUser->is_locked || (!empty($currentUser->email) && !str_ends_with(strtolower(trim($currentUser->email)), '@gmail.com') && !$isAdmin)) {
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa do vi phạm chính sách.');
            }

            if (!$isAdmin) {
                // 1. Kiểm tra giới hạn hủy đơn: Tối đa 3 lần hủy
                $cancelledOrdersCount = Order::where(function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id)
                      ->orWhere('customer_email', $currentUser->email);
                })->where('status', 'cancelled')->count();

                if ($cancelledOrdersCount >= 3) {
                    return redirect()->route('user.orders')->with([
                        'error' => 'Tài khoản của bạn đã đạt giới hạn 3 lần hủy đơn thanh toán. Vui lòng liên hệ Admin để được hỗ trợ mua hàng!',
                        'show_cancelled_limit_modal' => true,
                    ]);
                }

                // 2. Giới hạn đơn chờ: Tối đa 2 đơn đang xử lý
                $pendingOrdersCount = Order::where(function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id)
                      ->orWhere('customer_email', $currentUser->email);
                })->whereIn('status', ['pending', 'processing'])->count();

                if ($pendingOrdersCount >= 2) {
                    return redirect()->route('user.orders')->with([
                        'error' => 'Tài khoản của bạn hiện đang có 2 đơn hàng đang chờ xử lý. Vui lòng chờ 2 đơn hàng này hoàn tất trước khi tạo thêm đơn mới!',
                        'show_pending_limit_modal' => true,
                    ]);
                }
            }
        }

        // Honeypot check (nếu bot tự động điền trường ẩn)
        if ($request->filled('hp_company_field') || $request->filled('fax_number')) {
            \Log::warning("Bot detected via honeypot in placeOrder from IP: " . $request->ip());
            abort(403, 'Yêu cầu không hợp lệ.');
        }

        // Chống spam đặt hàng theo IP: Tối đa 5 lần đặt hàng trong 15 phút từ 1 IP
        $clientIp = (string) ($request->header('cf-connecting-ip') ?: $request->ip());
        $ipOrderKey = 'ip_order_rate_' . md5($clientIp);
        $orderAttempts = (int) \Illuminate\Support\Facades\Cache::get($ipOrderKey, 0);
        if ($orderAttempts >= 5) {
            return redirect()->back()->with('error', 'Bạn đã thực hiện quá nhiều yêu cầu đặt hàng. Vui lòng chờ 15 phút trước khi thử lại!');
        }

        // Kiểm tra loại đơn hàng
        $hasPhysical = false;
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            if($product && $product->delivery_type === 'physical') {
                $hasPhysical = true;
                break;
            }
        }
        
        // Validate theo loại đơn hàng & Bắt buộc email @gmail.com
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower(trim($value)), '@gmail.com')) {
                        $fail('Địa chỉ email bắt buộc phải có đuôi @gmail.com!');
                    }
                }
            ],
            'customer_phone' => 'required|string|max:50',
        ];
        
        if($hasPhysical) {
            $rules['customer_address'] = 'required';
        }
        
        $request->validate($rules);

        $isEn = app()->getLocale() === 'en';
        $currency = $isEn ? 'USD' : 'VND';

        $totalAmount = 0;
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            $price = $product ? (float) $product->effective_price : (float) $details['price'];
            $totalAmount += $price * $details['quantity'];
        }

        $customerAddress = $request->customer_address ?? ($isEn ? 'Digital product - no shipping required' : 'Sản phẩm số - không cần giao hàng');
        if ($request->filled('customer_zalo')) {
            $customerAddress .= "\nZalo: " . $request->customer_zalo;
        }
        if ($request->filled('customer_facebook')) {
            $customerAddress .= "\nFacebook: " . $request->customer_facebook;
        }
        if ($request->filled('upgrade_email_or_note')) {
            $customerAddress .= "\n" . ($isEn ? 'Upgrade Email/Note: ' : 'Email cần nâng cấp/Ghi chú: ') . $request->upgrade_email_or_note;
        }
        if ($request->filled('payment_method')) {
            $pm = $request->payment_method;
            if ($pm === 'crypto') {
                $customerAddress .= "\n" . ($isEn ? 'Payment Method: Crypto Wallet (USDT,...)' : 'Phương thức thanh toán: Ví Crypto (USDT,...)');
            } elseif ($pm === 'binance_uid') {
                $customerAddress .= "\n" . ($isEn ? 'Payment Method: Binance UID' : 'Phương thức thanh toán: Binance UID');
            } elseif ($pm === 'paypal') {
                $customerAddress .= "\n" . ($isEn ? 'Payment Method: PayPal' : 'Phương thức thanh toán: PayPal');
            } else {
                $customerAddress .= "\n" . ($isEn ? 'Payment Method: VietQR Bank Transfer' : 'Phương thức thanh toán: Chuyển khoản VietQR');
            }
        }

        // Kiểm tra xem có sản phẩm/gói dịch vụ nào thiếu kho không
        $outOfStockMessages = [];
        foreach($cart as $id => $details) {
            $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
            $variantId = $details['variant_id'] ?? null;
            $product = Product::find($productId);

            if(!$product) {
                $outOfStockMessages[] = "Sản phẩm không tồn tại.";
                continue;
            }

            $currentStock = $product->stock;
            $itemName = $product->name;

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant) {
                    $outOfStockMessages[] = "Gói dịch vụ không tồn tại.";
                    continue;
                }
                $currentStock = $variant->stock;
                $itemName = $product->name . ' (' . $variant->name . ')';
            } elseif ($product->hasVariants()) {
                $outOfStockMessages[] = "Sản phẩm '{$product->name}' yêu cầu chọn gói dịch vụ cụ thể trước khi thanh toán.";
                continue;
            }

            if ($currentStock < $details['quantity']) {
                if ($currentStock <= 0) {
                    $outOfStockMessages[] = "Sản phẩm '{$itemName}' đã hết hàng.";
                } else {
                    $outOfStockMessages[] = "Sản phẩm '{$itemName}' chỉ còn {$currentStock} sản phẩm trong kho.";
                }
            }
        }

        if (!empty($outOfStockMessages)) {
            return redirect()->route('cart.index')->with('error', implode(' ', $outOfStockMessages));
        }

        $orderStatus = 'pending';

        // Check if there is a pre-existing transaction matching this order code in cache
        $orderCode = $request->order_code ?? session('checkout_order_code');
        if ($orderCode && \Illuminate\Support\Facades\Cache::has('sepay_payment_' . $orderCode)) {
            $cached = \Illuminate\Support\Facades\Cache::get('sepay_payment_' . $orderCode);
            // Calculate expected total amount in VND to compare
            $expectedAmount = 0;
            foreach ($cart as $details) {
                $expectedAmount += $details['price'] * $details['quantity'];
            }
            if (session()->has('applied_coupon')) {
                $couponId = session('applied_coupon');
                $coupon = \App\Models\Coupon::find($couponId);
                if ($coupon) {
                    $expectedAmount = max(0, $expectedAmount - (float)$coupon->value);
                }
            }
            if ($isEn) {
                $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                $expectedAmount = $expectedAmount * $rate;
            }

            if ($cached['amount'] >= ($expectedAmount * 0.95)) {
                $orderStatus = 'processing';
                \Illuminate\Support\Facades\Cache::forget('sepay_payment_' . $orderCode);
                \Illuminate\Support\Facades\Log::info("CartController: Auto-completing order {$orderCode} from Cache during checkout placement.");
            }
        }

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
                    if ($currency === 'USD') {
                        $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                        $discountAmount = $discountAmount / $rate;
                    }
                }
            }
            $finalTotal = max(0, $totalAmount - $discountAmount);

            $orderCodeVal = (session('checkout_order_code') ?: ($request->filled('order_code') && preg_match('/^DT-[A-Z0-9]{8}$/i', $request->order_code) ? strtoupper($request->order_code) : ('DT-' . strtoupper(\Illuminate\Support\Str::random(8)))));

            $formData = [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'upgrade_email_or_note' => $request->upgrade_email_or_note,
                'customer_zalo' => $request->customer_zalo,
                'customer_facebook' => $request->customer_facebook,
                'use_boxchat' => $request->boolean('use_boxchat'),
                'payment_method' => $request->payment_method ?? 'vietqr',
                'coupon_code' => $coupon ? $coupon->code : null,
                'discount_amount' => $discountAmount,
                'ip' => $request->ip(),
            ];

            $order = Order::where('order_code', $orderCodeVal)->first();

            if ($order) {
                $order->update([
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $customerAddress,
                    'form_data' => $formData,
                    'total_amount' => $finalTotal,
                    'currency' => $currency,
                    'status' => $orderStatus,
                    'status_note' => $orderStatus === 'processing' ? 'Thanh toán thành công' : 'Đang chờ xử lý',
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                ]);

                // Đảm bảo các sản phẩm trong đơn hàng
                if ($order->orderItems()->count() === 0) {
                    foreach($cart as $id => $details) {
                        $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
                        $variantId = $details['variant_id'] ?? null;
                        $variantName = $details['variant_name'] ?? null;
                        $price = (float) $details['price'];

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $productId,
                            'variant_id' => $variantId,
                            'variant_name' => $variantName,
                            'quantity' => $details['quantity'],
                            'price' => $price,
                        ]);
                    }
                }

                // Trừ tồn kho variant nếu có
                foreach($cart as $id => $details) {
                    $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
                    $variantId = $details['variant_id'] ?? null;

                    if ($variantId) {
                        $variant = ProductVariant::find($variantId);
                        if ($variant && $variant->stock >= $details['quantity']) {
                            $variant->decrement('stock', $details['quantity']);
                        }
                    }

                    // Trừ tồn kho nếu sản phẩm còn hàng sẵn
                    $product = Product::find($productId);
                    if ($product && $product->stock >= $details['quantity']) {
                        $product->decrement('stock', $details['quantity']);
                    }
                }
            } else {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $customerAddress,
                    'form_data' => $formData,
                    'total_amount' => $finalTotal,
                    'currency' => $currency,
                    'status' => $orderStatus,
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'order_code' => $orderCodeVal,
                ]);

                foreach($cart as $id => $details) {
                    $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
                    $variantId = $details['variant_id'] ?? null;
                    $variantName = $details['variant_name'] ?? null;
                    $price = (float) $details['price'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'variant_name' => $variantName,
                        'quantity' => $details['quantity'],
                        'price' => $price,
                    ]);

                    // Trừ tồn kho variant nếu có
                    if ($variantId) {
                        $variant = ProductVariant::find($variantId);
                        if ($variant && $variant->stock >= $details['quantity']) {
                            $variant->decrement('stock', $details['quantity']);
                        }
                    }

                    // Trừ tồn kho nếu sản phẩm còn hàng sẵn
                    $product = Product::find($productId);
                    if ($product && $product->stock >= $details['quantity']) {
                        $product->decrement('stock', $details['quantity']);
                    }
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
            
            // Ghi nhận lượt đặt hàng thành công của IP
            \Illuminate\Support\Facades\Cache::put($ipOrderKey, $orderAttempts + 1, now()->addMinutes(15));
            
            // Clear the "new user" flag after order is placed
            session()->forget('is_new_user');
            session()->forget('checkout_order_code');
            
            // Gửi email duyệt đơn tự động nếu đơn hàng thành công (completed) hoặc đang xử lý (processing)
            if ($orderStatus === 'completed' || $orderStatus === 'processing') {
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
            } elseif ($orderStatus === 'processing') {
                return redirect()->route('user.orders')->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đang được xử lý.');
            } else {
                return redirect()->route('user.orders')->with('success', 'Đặt hàng thành công! Vui lòng chờ admin xác nhận.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }
    }

    /**
     * Gửi thông tin toàn bộ form bước 1 qua Telegram & Email cho Admin trước khi sang màn hình quét QR
     */
    public function preNotify(Request $request)
    {
        // 1. Kiểm tra giỏ hàng - Bắt buộc không được trống
        $cart = session()->get('cart', []);
        if (empty($cart) || !is_array($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn đang trống!'
            ], 400);
        }

        // 2. Kiểm tra tài khoản: Nếu bị khóa hoặc email không phải @gmail.com thì chặn ngay
        if (auth()->check()) {
            $currentUser = auth()->user();
            $isAdmin = in_array($currentUser->role, ['superadmin_1', 'sieusuperadmin', 'admin', 'blog_editor'], true) || (bool) $currentUser->is_admin;

            if ($currentUser->is_locked || (!empty($currentUser->email) && !str_ends_with(strtolower(trim($currentUser->email)), '@gmail.com') && !$isAdmin)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa do vi phạm chính sách.'
                ], 403);
            }

            if (!$isAdmin) {
                // Kiểm tra giới hạn 3 lần hủy đơn
                $cancelledOrdersCount = Order::where(function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id)
                      ->orWhere('customer_email', $currentUser->email);
                })->where('status', 'cancelled')->count();

                if ($cancelledOrdersCount >= 3) {
                    return response()->json([
                        'success' => false,
                        'reason' => 'cancelled_limit',
                        'message' => 'Tài khoản của bạn đã đạt giới hạn 3 lần hủy đơn thanh toán và bị tạm khóa đặt hàng vì nghi ngờ vi phạm. Vui lòng liên hệ Admin để được hỗ trợ mua hàng!'
                    ], 403);
                }

                // Giới hạn tối đa 2 đơn đang xử lý
                $pendingOrdersCount = Order::where(function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id)
                      ->orWhere('customer_email', $currentUser->email);
                })->whereIn('status', ['pending', 'processing'])->count();

                if ($pendingOrdersCount >= 2) {
                    return response()->json([
                        'success' => false,
                        'reason' => 'pending_limit',
                        'message' => 'Bạn đang có 2 đơn hàng đang chờ xử lý. Vui lòng chờ 2 đơn hàng này hoàn tất trước khi tạo thêm đơn mới!'
                    ], 403);
                }
            }
        }

        // 3. Honeypot check (nếu bot tự động điền trường ẩn)
        if ($request->filled('hp_company_field') || $request->filled('fax_number')) {
            \Log::warning("Bot detected via honeypot in preNotify from IP: " . $request->ip());
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ.'
            ], 403);
        }

        // 4. Giới hạn tần suất gửi theo IP (chống spam bot)
        $clientIp = (string) ($request->header('cf-connecting-ip') ?: $request->ip());
        $ipPreKey = 'pre_notify_rate_' . md5($clientIp);
        $attempts = (int) \Illuminate\Support\Facades\Cache::get($ipPreKey, 0);
        if ($attempts >= 6) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi yêu cầu quá nhiều lần. Vui lòng chờ 15 phút.'
            ], 429);
        }
        \Illuminate\Support\Facades\Cache::put($ipPreKey, $attempts + 1, now()->addMinutes(15));

        // 5. Validate dữ liệu & Bắt buộc email @gmail.com
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower(trim($value)), '@gmail.com')) {
                        $fail('Địa chỉ email bắt buộc phải có đuôi @gmail.com!');
                    }
                }
            ],
            'customer_phone' => 'required|string|max:50',
            'upgrade_email_or_note' => 'nullable|string|max:1000',
            'customer_zalo' => 'nullable|string|max:100',
            'customer_facebook' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:500',
            'use_boxchat' => 'nullable',
            'order_code' => 'nullable|string|max:50',
        ]);

        $orderCode = $request->order_code ?: session('checkout_order_code', 'DT-' . strtoupper(\Illuminate\Support\Str::random(8)));

        // Lưu thông tin khách hàng vào session tạm để không bao giờ bị mất
        session()->put('pre_checkout_customer', [
            'name' => $request->customer_name,
            'email' => $request->customer_email,
            'phone' => $request->customer_phone,
            'note' => $request->upgrade_email_or_note,
            'zalo' => $request->customer_zalo,
            'facebook' => $request->customer_facebook,
            'address' => $request->customer_address,
            'use_boxchat' => $request->boolean('use_boxchat'),
        ]);

        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;
        $isEn = (app()->getLocale() === 'en');

        foreach ($cart as $id => $details) {
            $price = (float) $details['price'];
            $qty = (int) ($details['quantity'] ?? 1);
            $lineTotal = $price * $qty;
            $total += $lineTotal;

            $items[] = [
                'name' => $details['name'] ?? 'Sản phẩm',
                'quantity' => $qty,
                'price' => $price,
                'price_formatted' => $isEn ? ('$' . number_format($price, 2)) : (number_format($price, 0, ',', '.') . 'đ'),
                'total_formatted' => $isEn ? ('$' . number_format($lineTotal, 2)) : (number_format($lineTotal, 0, ',', '.') . 'đ'),
            ];
        }

        // Tính giảm giá nếu có coupon
        $discountAmount = 0;
        $couponCode = null;
        if (session()->has('applied_coupon')) {
            $coupon = \App\Models\Coupon::find(session('applied_coupon'));
            if ($coupon) {
                $discountAmount = (float) $coupon->value;
                if ($isEn) {
                    $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                    $discountAmount = $discountAmount / $rate;
                }
                $couponCode = $coupon->code;
            }
        }

        $finalTotal = max(0, $total - $discountAmount);
        $totalFormatted = $isEn ? ('$' . number_format($finalTotal, 2)) : (number_format($finalTotal, 0, ',', '.') . 'đ');
        $discountFormatted = $discountAmount > 0 ? ($isEn ? ('$' . number_format($discountAmount, 2)) : (number_format($discountAmount, 0, ',', '.') . 'đ')) : null;

        $payload = [
            'order_code' => $orderCode,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'upgrade_email_or_note' => $request->upgrade_email_or_note,
            'customer_zalo' => $request->customer_zalo,
            'customer_facebook' => $request->customer_facebook,
            'customer_address' => $request->customer_address,
            'use_boxchat' => $request->boolean('use_boxchat'),
            'items' => $items,
            'coupon_code' => $couponCode,
            'discount_amount' => $discountAmount,
            'discount_formatted' => $discountFormatted,
            'total_amount' => $finalTotal,
            'total_formatted' => $totalFormatted,
            'ip' => $request->ip(),
            'time' => now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s'),
        ];

        // 1. Gửi Telegram cho Admin
        try {
            TelegramHelper::sendPreCheckoutNotification($payload);
        } catch (\Exception $e) {
            \Log::error('Error sending pre-checkout Telegram notification: ' . $e->getMessage());
        }

        // 2. Gửi Email cho Admin: tranthanhtuanfix@gmail.com
        try {
            Mail::to('tranthanhtuanfix@gmail.com')->send(new PreCheckoutAdminMail($payload));
            \Log::info('Pre-checkout admin email sent to tranthanhtuanfix@gmail.com for ' . $orderCode);
        } catch (\Exception $e) {
            \Log::error('Error sending pre-checkout admin email: ' . $e->getMessage());
        }

        // 3. Ghi nhận đơn hàng ở trạng thái 'cancelled' (Chưa thanh toán) vào DB
        // Để khi khách hàng out ra không mua thì trong lịch sử đơn hàng của khách và quản lý admin sẽ hiển thị trạng thái đã hủy
        try {
            $customerAddress = $request->customer_address ?? ($isEn ? 'Digital product - no shipping required' : 'Sản phẩm số - không cần giao hàng');
            if ($request->filled('customer_zalo')) {
                $customerAddress .= "\nZalo: " . $request->customer_zalo;
            }
            if ($request->filled('customer_facebook')) {
                $customerAddress .= "\nFacebook: " . $request->customer_facebook;
            }
            if ($request->filled('upgrade_email_or_note')) {
                $customerAddress .= "\n" . ($isEn ? 'Upgrade Email/Note: ' : 'Email cần nâng cấp/Ghi chú: ') . $request->upgrade_email_or_note;
            }
            if ($request->boolean('use_boxchat')) {
                $customerAddress .= "\n" . ($isEn ? 'Boxchat: Yes (Contact via Boxchat)' : 'Liên hệ: Có (Khách tự liên hệ qua Boxchat)');
            }
            if ($request->filled('payment_method')) {
                $customerAddress .= "\n" . ($isEn ? 'Payment Method: ' : 'Phương thức thanh toán: ') . $request->payment_method;
            }

            $formData = [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'upgrade_email_or_note' => $request->upgrade_email_or_note,
                'customer_zalo' => $request->customer_zalo,
                'customer_facebook' => $request->customer_facebook,
                'use_boxchat' => $request->boolean('use_boxchat'),
                'payment_method' => $request->payment_method ?? 'vietqr',
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'ip' => $request->ip(),
            ];

            $order = Order::where('order_code', $orderCode)->first();
            if ($order) {
                if ($order->status !== 'completed' && $order->status !== 'processing') {
                    $order->update([
                        'user_id' => auth()->id(),
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email,
                        'customer_phone' => $request->customer_phone,
                        'customer_address' => $customerAddress,
                        'form_data' => $formData,
                        'total_amount' => $finalTotal,
                        'currency' => $isEn ? 'USD' : 'VND',
                        'status' => 'cancelled',
                        'status_note' => 'Chưa thanh toán (Khách dừng tại màn hình QR)',
                        'coupon_code' => $couponCode,
                        'discount_amount' => $discountAmount,
                    ]);
                }
            } else {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $customerAddress,
                    'form_data' => $formData,
                    'total_amount' => $finalTotal,
                    'currency' => $isEn ? 'USD' : 'VND',
                    'status' => 'cancelled',
                    'status_note' => 'Chưa thanh toán (Khách dừng tại màn hình QR)',
                    'coupon_code' => $couponCode,
                    'discount_amount' => $discountAmount,
                    'order_code' => $orderCode,
                ]);

                foreach ($cart as $id => $details) {
                    $productId = $details['product_id'] ?? (is_numeric($id) ? (int)$id : (int)explode('_', $id)[0]);
                    $variantId = $details['variant_id'] ?? null;
                    $variantName = $details['variant_name'] ?? null;
                    $price = (float) $details['price'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'variant_name' => $variantName,
                        'quantity' => $details['quantity'],
                        'price' => $price,
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error recording pre-checkout unpaid order in preNotify: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pre-checkout notification sent successfully',
            'order_code' => $orderCode
        ]);
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

