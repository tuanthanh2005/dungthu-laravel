?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Blog;
use App\Models\Message;
use App\Models\AbandonedCart;
use App\Models\CardExchange;
use App\Models\SiteSetting;
use App\Models\ProductCategory;
use App\Models\Affiliate;
use App\Models\AffiliateInvoice;
use App\Models\AffiliateWithdrawal;
use Illuminate\Support\Facades\Mail;
use App\Mail\SystemNotificationMail;
use App\Mail\OrderApprovedMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\AbandonedCartReminder;
use App\Helpers\TelegramHelper;
use App\Helpers\PathHelper;
use App\Services\GoogleIndexingService;

class AdminController extends Controller
{


    // Hàm crop ảnh về kích thước chuẩn
    private function cropImage($file, $width = 500, $height = 334)
    {
        $image = imagecreatefromstring(file_get_contents($file));
        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);
        
        // Tính toán kích thước crop để giữ tỷ lệ 500:334
        $targetRatio = $width / $height;
        $srcRatio = $srcWidth / $srcHeight;
        
        if ($srcRatio > $targetRatio) {
            // Ảnh rộng hơn, crop theo chiều ngang
            $cropHeight = $srcHeight;
            $cropWidth = $srcHeight * $targetRatio;
            $srcX = ($srcWidth - $cropWidth) / 2;
            $srcY = 0;
        } else {
            // Ảnh cao hơn, crop theo chiều dọc
            $cropWidth = $srcWidth;
            $cropHeight = $srcWidth / $targetRatio;
            $srcX = 0;
            $srcY = ($srcHeight - $cropHeight) / 2;
        }
        
        // Tạo ảnh mới với kích thước chuẩn
        $newImage = imagecreatetruecolor($width, $height);
        
        // Giữ trong suốt cho PNG
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        
        // Crop và resize
        imagecopyresampled(
            $newImage, $image,
            0, 0, $srcX, $srcY,
            $width, $height, $cropWidth, $cropHeight
        );
        
        return $newImage;
    }
    
    // Lưu ảnh đã crop
    private function saveImage($image, $path, $extension)
    {
        switch(strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $path, 90);
                break;
            case 'png':
                imagepng($image, $path, 8);
                break;
            case 'gif':
                imagegif($image, $path);
                break;
            default:
                imagejpeg($image, $path, 90);
        }
        imagedestroy($image);
    }

    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::where('role', 'user')->count(),
            'blogs' => Blog::count(),
        ];

        $unreadChatCount = Message::where('is_admin', false)
            ->where('is_read', false)
            ->count();

        // Đếm số đơn hàng đang chờ xử lý (pending)
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // Đếm số yêu cầu đổi thẻ cào đang chờ xử lý
        $pendingCardExchangeCount = CardExchange::where('status', 'pending')->count();

        // Đếm số giỏ hàng bị bỏ quên (chưa gửi reminder lần 3)
        $abandonedCartsCount = AbandonedCart::where('reminder_stage', '<', 3)->count();
        
        $latestOrders = Order::with(['user', 'orderItems.product'])->latest()->take(5)->get();
        
        // Tính toán doanh thu (chỉ tính các đơn hàng đã hoàn thành)
        $revenue30Days = Order::where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(30))
            ->sum('total_amount');
            
        $revenue10Days = Order::where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(10))
            ->sum('total_amount');
            
        $revenue5Days = Order::where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(5))
            ->sum('total_amount');

        $revenue60Days = Order::where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(60))
            ->sum('total_amount');

        $revenue90Days = Order::where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(90))
            ->sum('total_amount');

        // Hàm helper sinh dữ liệu biểu đồ theo số ngày dựa trên thời gian done (completed) của đơn hàng
        $getChartData = function($days) {
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $label = now()->subDays($i)->format('d/m');
                $data[$label] = 0;
            }

            $revenue = Order::where('status', 'completed')
                ->where('updated_at', '>=', now()->subDays($days))
                ->selectRaw('DATE(updated_at) as date, SUM(total_amount) as total')
                ->groupBy('date')
                ->get();

            foreach ($revenue as $row) {
                $label = \Carbon\Carbon::parse($row->date)->format('d/m');
                if (isset($data[$label])) {
                    $data[$label] = (float)$row->total;
                }
            }
            return [
                'labels' => array_keys($data),
                'values' => array_values($data),
            ];
        };

        $chart5Days = $getChartData(5);
        $chart10Days = $getChartData(10);
        $chart30Days = $getChartData(30);
        $chart60Days = $getChartData(60);
        $chart90Days = $getChartData(90);

        // Affiliate stats
        $pendingAffCount = Affiliate::where('status', 'pending')->count();
        $pendingAffInvoiceCount = AffiliateInvoice::where('status', 'pending')->count();
        $pendingAffWithdrawCount = AffiliateWithdrawal::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact(
            'stats', 
            'latestOrders', 
            'unreadChatCount',
            'pendingOrdersCount',
            'pendingCardExchangeCount',
            'abandonedCartsCount',
            'revenue30Days',
            'revenue10Days',
            'revenue5Days',
            'revenue60Days',
            'revenue90Days',
            'pendingAffCount',
            'pendingAffInvoiceCount',
            'pendingAffWithdrawCount',
            'chart5Days',
            'chart10Days',
            'chart30Days',
            'chart60Days',
            'chart90Days'
        ));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total_amount');
        
        // Support search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_orders':
                $query->orderBy('orders_count', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'most_spent':
                $query->orderBy('orders_sum_total_amount', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $users = $query->paginate(10)->appends($request->query());
        
        return view('admin.users.index', compact('users'));
    }

    public function abandonedCarts()
    {
        $carts = AbandonedCart::query()
            ->with('user')
            ->orderByDesc('last_activity_at')
            ->paginate(20);

        return view('admin.abandoned-carts.index', compact('carts'));
    }

    public function sendAbandonedCartReminders(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:abandoned_carts,id',
            'message' => 'required|string'
        ], [
            'cart_ids.required' => 'Vui lòng chọn ít nhất 1 giỏ hàng để gửi.',
            'message.required' => 'Vui lòng nhập nội dung thông báo.'
        ]);

        $carts = AbandonedCart::whereIn('id', $request->cart_ids)->with('user')->get();
        $successCount = 0;

        foreach ($carts as $cart) {
            try {
                if ($cart->email) {
                    Mail::to($cart->email)->send(new AbandonedCartReminder($cart, $request->message));
                    
                    // Cập nhật số lần nhắc nhở
                    $cart->increment('reminder_stage');
                    $cart->update(['last_reminder_at' => now()]);
                    
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error('Error sending abandoned cart reminder to ' . $cart->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', "Đã gửi thông báo thành công tới {$successCount} khách hàng!");
    }

    public function systemNotifications()
    {
        // Lấy danh sách user để gửi thông báo
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.system-notifications.index', compact('users'));
    }

    public function sendSystemNotifications(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ], [
            'user_ids.required' => 'Vui lòng chọn ít nhất 1 người dùng để gửi.',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'message.required' => 'Vui lòng nhập nội dung thông báo.'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $successCount = 0;

        foreach ($users as $user) {
            try {
                if ($user->email) {
                    Mail::to($user->email)->send(new SystemNotificationMail($user, $request->subject, $request->message));
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error('Error sending system notification to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', "Đã gửi thông báo hệ thống thành công tới {$successCount} người dùng!");
    }

    public function userHistory($id)
    {
        $user = User::with(['orders.orderItems.product'])
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->findOrFail($id);
        
        $orders = Order::where('user_id', $id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.users.history', compact('user', 'orders'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin,moderator',
        ], [
            'role.required' => 'Quyền không được để trống',
            'role.in' => 'Quyền không hợp lệ',
        ]);

        // Prevent users from removing their own admin role
        if (auth()->id() === $user->id && $request->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi quyền của chính mình'
            ], 403);
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => "Cập nhật quyền thành công! ({$oldRole} → {$request->role})",
            'role' => $user->role
        ]);
    }

    /**
     * Award (or deduct) spin tickets for a user.
     */
    public function awardSpinTickets(Request $request, User $user)
    {
        $request->validate([
            'tickets' => 'required|integer',
        ], [
            'tickets.required' => 'Số vé không được để trống',
            'tickets.integer' => 'Số vé phải là số nguyên',
        ]);

        $change = (int) $request->tickets;
        
        // Update user tickets (ensure it does not go below 0)
        $newTickets = max(0, $user->spin_tickets + $change);
        $user->update(['spin_tickets' => $newTickets]);

        $msg = $change >= 0 
            ? "Đã cấp thêm {$change} lượt quay cho {$user->name}." 
            : "Đã trừ " . abs($change) . " lượt quay của {$user->name}.";

        return response()->json([
            'success' => true,
            'message' => $msg . " Tổng số vé hiện tại: {$newTickets}.",
            'spin_tickets' => $newTickets
        ]);
    }

    /**
     * Generate a new unassigned coupon.
     */
    public function generateCoupon(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:1000',
        ], [
            'value.required' => 'Mệnh giá không được để trống',
            'value.numeric' => 'Mệnh giá phải là số tiền hợp lệ',
            'value.min' => 'Mệnh giá tối thiểu là 1.000đ',
        ]);

        $value = (float) $request->value;
        $valStr = ($value >= 1000) ? ($value / 1000) . 'K' : $value . 'đ';
        
        // Generate code: ADMIN[Val]-XXXXXX
        $code = 'ADMIN' . strtoupper($valStr) . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        // Create the coupon (unassigned, user_id = null)
        $coupon = \App\Models\Coupon::create([
            'code' => $code,
            'value' => $value,
            'user_id' => null,
            'is_used' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo mã Voucher thành công!',
            'coupon' => [
                'code' => $coupon->code,
                'value' => $coupon->value,
                'formatted_value' => number_format($coupon->value, 0, ',', '.') . 'đ',
            ]
        ]);
    }


    // Order Management
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Support search by ID, name, email or phone
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo loại đơn hàng
        if ($request->has('type') && $request->type !== 'all') {
            $query->byType($request->type);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->appends($request->query());
        
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Nếu đơn hàng được chuyển sang trạng thái completed, gửi email thông báo duyệt đơn + telegram
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            // Gửi email cảm ơn / xác nhận duyệt đơn tới khách hàng
            $this->sendOrderApprovedEmail($order);

            // Gửi thông báo Telegram nội bộ
            $this->sendOrderCompletedTelegram($order);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function deliverOrder(Request $request, Order $order)
    {
        $request->validate([
            'delivery_account' => 'nullable|string',
            'delivery_key' => 'nullable|string',
            'delivery_note' => 'nullable|string',
        ]);

        $oldStatus = $order->status;

        $order->update([
            'delivery_account' => $request->delivery_account,
            'delivery_key' => $request->delivery_key,
            'delivery_note' => $request->delivery_note,
            'status' => 'completed',
        ]);

        // Gửi email bàn giao
        try {
            $email = $order->customer_email;
            if (!$email && $order->user_id) {
                $email = optional($order->user)->email;
            }

            if ($email) {
                $order->load('orderItems.product');
                Mail::to($email)->send(new OrderDeliveredMail($order));
                \Log::info('Order delivered email sent to ' . $email . ' for order #' . $order->id);
            }
        } catch (\Exception $e) {
            \Log::error('Error sending order delivered email for order #' . $order->id . ': ' . $e->getMessage());
        }

        // Nếu trạng thái cũ chưa phải completed, gửi thông báo Telegram để tránh trùng lặp
        if ($oldStatus !== 'completed') {
            $this->sendOrderCompletedTelegram($order);
        }

        return redirect()->back()->with('success', 'Giao hàng và gửi email thông tin bàn giao thành công!');
    }

    /**
     * Gửi email thông báo duyệt đơn thành công tới khách hàng
     */
    private function sendOrderApprovedEmail(Order $order)
    {
        try {
            $email = $order->customer_email;

            if (!$email && $order->user_id) {
                $email = optional($order->user)->email;
            }

            if ($email) {
                $order->load('orderItems.product');
                Mail::to($email)->send(new OrderApprovedMail($order));
                \Log::info('Order approved email sent to ' . $email . ' for order #' . $order->id);
            }
        } catch (\Exception $e) {
            \Log::error('Error sending order approved email for order #' . $order->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Gửi thông báo Telegram khi đơn hàng hoàn thành
     */
    private function sendOrderCompletedTelegram(Order $order)
    {
        try {
            $telegramMessage = $this->formatCompletedOrderTelegramMessage($order);
            TelegramHelper::sendMessage($telegramMessage);
        } catch (\Exception $e) {
            \Log::error('Error sending order completed Telegram: ' . $e->getMessage());
        }
    }

    /**
     * Format thông báo Telegram cho đơn hàng completed
     */
    private function formatCompletedOrderTelegramMessage(Order $order)
    {
        $order->load('orderItems.product');

        $message = "✅ <b>ĐƠN HÀNG ĐÃ ĐƯỢC DUYỆT</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Thông tin đơn hàng
        $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
        $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
        if (isset($order->discount_amount) && $order->discount_amount > 0) {
            $message .= "• Giảm giá: <b>-" . number_format($order->discount_amount, 0, ',', '.') . "đ</b> (" . $order->coupon_code . ")\n";
        }
        $message .= "• Tổng tiền: <b>" . number_format((float)$order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";

        // Thông tin khách hàng
        $message .= "👤 <b>KHÁCH HÀNG</b>\n";
        $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
        $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
        $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n\n";

        // Sản phẩm
        $message .= "🛒 <b>SẢN PHẨM</b>\n";
        foreach ($order->orderItems as $index => $item) {
            $message .= ($index + 1) . ". " . ($item->product->name ?? 'N/A') . "\n";
            $message .= "   • SL: " . $item->quantity . " | Giá: " . number_format($item->price, 0, ',', '.') . "đ\n";
        }

        $message .= "\n📧 Email xác nhận đã được gửi tới khách hàng!";

        return $message;
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'Xóa đơn hàng thành công!');
    }

    // Product Management
    public function products(Request $request)
    {
        $query = Product::query()->with('categoryRelation');
        
        // Search support
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter flash sale products
        if ($request->filled('flash_sale')) {
            $query->where('is_flash_sale', true);
        }
        
        $products = $query->latest()->paginate(10)->appends($request->query());
        $flashSaleEnabled = SiteSetting::getValue('flash_sale_enabled', '1') === '1';
        return view('admin.products.index', compact('products', 'flashSaleEnabled'));
    }

    public function toggleFlashSaleGlobal()
    {
        $current = SiteSetting::getValue('flash_sale_enabled', '1');
        $next = $current === '1' ? '0' : '1';
        SiteSetting::setValue('flash_sale_enabled', $next);

        $message = $next === '1'
            ? 'ÄÃ£ báº­t Flash Sale toÃ n bá»™.'
            : 'ÄÃ£ táº¯t Flash Sale toÃ n bá»™.';

        return redirect()->back()->with('success', $message);
    }

    public function toggleProductFlashSale(Product $product)
    {
        $product->is_flash_sale = !$product->is_flash_sale;
        $product->save();
        return redirect()->back()->with('success', 'Cập nhật Flash Sale thành công!');
    }

    public function toggleProductFeatured(Product $product)
    {
        $product->is_featured = !$product->is_featured;
        $product->save();
        return redirect()->back()->with('success', 'Cập nhật Nổi bật thành công!');
    }

    public function toggleProductExclusive(Product $product)
    {
        $product->is_exclusive = !$product->is_exclusive;
        $product->save();
        return redirect()->back()->with('success', 'Cập nhật Độc quyền thành công!');
    }

    public function toggleProductComboAi(Product $product)
    {
        $product->is_combo_ai = !$product->is_combo_ai;
        $product->save();
        return redirect()->back()->with('success', 'Cập nhật Combo AI thành công!');
    }

    public function oldToggleProductFlashSale(Product $product)
    {
        $product->is_flash_sale = !$product->is_flash_sale;
        $product->save();

        $message = $product->is_flash_sale
            ? 'ÄÃ£ báº­t Flash Sale cho sáº£n pháº©m.'
            : 'ÄÃ£ táº¯t Flash Sale cho sáº£n pháº©m.';

        return redirect()->back()->with('success', $message);
    }

    public function createProduct($category = null)
    {
        if ($category === null) {
            return view('admin.products.create-select');
        }

        if ($category && !in_array($category, ['tech', 'ebooks', 'doc'])) {
            abort(404);
        }
        
        // Lấy danh sách features theo category
        $features = \App\Models\Feature::when($category, function($query) use ($category) {
            return $query->where('category', $category);
        })->get();

        $categories = ProductCategory::query()
            ->where('type', $category)
            ->orderBy('name')
            ->get();
        
        // Use a tech-specific form only when that Blade file exists.
        $viewName = $category === 'tech' && view()->exists('admin.products.create-tech')
            ? 'admin.products.create-tech'
            : 'admin.products.create';
        return view($viewName, compact('category', 'features', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_type' => 'required|in:digital,physical',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar|max:51200', // 50MB max
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'description.required' => 'Mô tả không được để trống',
              'price.required' => 'Giá không được để trống',
              'price.numeric' => 'Giá phải là số',
              'sale_price.numeric' => 'Giá giảm phải là số',
              'sale_price.lt' => 'Giá giảm phải nhỏ hơn giá gốc',
              'category_id.required' => 'Danh mục không được để trống',
              'category_id.exists' => 'Danh mục không hợp lệ',
              'stock.required' => 'Số lượng không được để trống',
              'stock.integer' => 'Số lượng phải là số nguyên',
              'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'file.mimes' => 'File phải có định dạng: pdf, doc, docx, txt, zip, rar',
            'file.max' => 'Kích thước file không được vượt quá 50MB',
        ]);

        $categoryRecord = ProductCategory::findOrFail($request->category_id);
        $categoryType = $categoryRecord->type;

        $slug = \Str::slug($request->name) . '-' . time();
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/products/' . $fileName);
            
            // Crop ảnh về kích thước 500x334
            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $imagePath = '/images/products/' . $fileName;
        }
        
        // Xử lý file upload cho ebooks
        $filePath = null;
        $fileType = null;
        $fileSize = null;
        
        if ($request->hasFile('file') && $categoryType === 'ebooks') {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . \Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Lấy size trước khi move
            $fileSize = round($file->getSize() / 1024); // Convert to KB
            
            // Lưu file vào public/files
            $file->move(PathHelper::publicRootPath('files'), $fileName);
            
            $filePath = $fileName;
            $fileType = $extension;
        }
        
        // Xử lý specs động từ spec_keys và spec_values
        $specs = [];
        $keys = $request->input('spec_keys', []);
        $values = $request->input('spec_values', []);

        foreach ($keys as $index => $key) {
            if (!empty($key) && !empty($values[$index])) {
                $specs[$key] = $values[$index];
            }
        }

        // Xử lý specs tiếng Anh
        $specsEn = [];
        $keysEn = $request->input('spec_keys_en', []);
        $valuesEn = $request->input('spec_values_en', []);

        foreach ($keysEn as $index => $key) {
            if (!empty($key) && !empty($valuesEn[$index])) {
                $specsEn[$key] = $valuesEn[$index];
            }
        }

        if (empty($specs) && $categoryType === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $fileType ?? 'PDF',
            ];
        } elseif (empty($specs) && $categoryType === 'doc') {
            $specs = [
                'paper_type' => $request->input('paper_type'),
                'size' => $request->input('size'),
                'packaging' => $request->input('packaging'),
                'origin' => $request->input('origin'),
            ];
        }

        if (empty($specsEn) && $categoryType === 'ebooks') {
            $specsEn = [
                'pages' => $request->input('pages'),
                'language' => 'English',
                'format' => $fileType ?? 'PDF',
            ];
        }

          $product = Product::create([
              'name' => $request->name,
              'name_en' => $request->name_en,
              'slug' => $slug,
              'description' => $request->description,
              'description_en' => $request->description_en,
              'price' => $request->price,
              'price_usd' => $request->price_usd,
              'sale_price' => $request->has('is_on_sale') && $request->filled('sale_price') ? $request->sale_price : null,
              'sale_price_usd' => $request->has('is_on_sale') && $request->filled('sale_price_usd') ? $request->sale_price_usd : null,
              'category' => $categoryType,
              'category_id' => $categoryRecord->id,
              'stock' => $request->stock,
              'image' => $imagePath ? asset($imagePath) : null,
              'file_path' => $filePath,
              'file_type' => $fileType,
            'file_size' => $fileSize,
            'specs' => $specs,
            'specs_en' => $specsEn,
            'delivery_type' => $request->delivery_type,
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_exclusive' => $request->has('is_exclusive') ? true : false,
            'is_combo_ai' => $request->has('is_combo_ai') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
            'is_vpn' => $request->has('is_vpn') ? true : false,
            'duration_months' => $request->duration_months,
        ]);

        // Sync features nếu có
        if ($request->has('features')) {
            $product->features()->sync($request->features);
        }

        // Submit to Google Indexing
        GoogleIndexingService::submitProductSafe($product, 'product_create');

        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function editProduct(Product $product)
    {
        // Lấy danh sách features theo category của product
        $features = \App\Models\Feature::where('category', $product->category)->get();

        $categories = ProductCategory::query()
            ->where('type', $product->category)
            ->orderBy('name')
            ->get();
        
        // Use a tech-specific form only when that Blade file exists.
        $viewName = $product->category === 'tech' && view()->exists('admin.products.edit-tech')
            ? 'admin.products.edit-tech'
            : 'admin.products.edit';
        return view($viewName, compact('product', 'features', 'categories'));
    }

      public function updateProduct(Request $request, Product $product)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'name_en' => 'nullable|string|max:255',
              'description' => 'required|string',
              'description_en' => 'nullable|string',
              'price' => 'required|numeric|min:0',
              'price_usd' => 'nullable|numeric|min:0',
              'sale_price' => 'nullable|numeric|min:0|lt:price',
              'sale_price_usd' => 'nullable|numeric|min:0',
              'category_id' => 'required|exists:product_categories,id',
              'stock' => 'required|integer|min:0',
              'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
              'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar|max:51200',
              'delivery_type' => 'required|in:digital,physical',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'description.required' => 'Mô tả không được để trống',
              'price.required' => 'Giá không được để trống',
              'price.numeric' => 'Giá phải là số',
              'sale_price.numeric' => 'Giá giảm phải là số',
              'sale_price.lt' => 'Giá giảm phải nhỏ hơn giá gốc',
              'category_id.required' => 'Danh mục không được để trống',
              'category_id.exists' => 'Danh mục không hợp lệ',
              'stock.required' => 'Số lượng không được để trống',
              'stock.integer' => 'Số lượng phải là số nguyên',
              'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'file.mimes' => 'File phải có định dạng: pdf, doc, docx, txt, zip, rar',
            'file.max' => 'Kích thước file không được vượt quá 50MB',
        ]);

        $categoryRecord = ProductCategory::findOrFail($request->category_id);
        $categoryType = $categoryRecord->type;

        $slug = \Str::slug($request->name) . '-' . $product->id;
        
        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                $oldImagePath = parse_url($product->image, PHP_URL_PATH);
                $fullPath = PathHelper::publicRootPath($oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/products/' . $fileName);
            
            // Crop ảnh về kích thước 500x334
            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $product->image = asset('/images/products/' . $fileName);
        }
        
        // Xử lý upload file mới cho ebooks
        if ($request->hasFile('file') && $categoryType === 'ebooks') {
            // Xóa file cũ nếu có
            if ($product->file_path) {
                $oldFilePath = PathHelper::publicRootPath('files/' . $product->file_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            
            // Lấy size trước khi move
            $fileSize = round($file->getSize() / 1024); // Convert to KB
            
            // Lưu file vào public/files
            $file->move(PathHelper::publicRootPath('files'), $fileName);
            
            $filePath = $fileName;
            $fileType = $extension;
        } else {
            // Keep existing file info if not uploading new file
            $filePath = $product->file_path;
            $fileType = $product->file_type;
            $fileSize = $product->file_size;
        }
        
        // Xử lý specs động từ spec_keys và spec_values
        $specs = [];
        $keys = $request->input('spec_keys', []);
        $values = $request->input('spec_values', []);

        foreach ($keys as $index => $key) {
            if (!empty($key) && !empty($values[$index])) {
                $specs[$key] = $values[$index];
            }
        }

        // Xử lý specs tiếng Anh
        $specsEn = [];
        $keysEn = $request->input('spec_keys_en', []);
        $valuesEn = $request->input('spec_values_en', []);

        foreach ($keysEn as $index => $key) {
            if (!empty($key) && !empty($valuesEn[$index])) {
                $specsEn[$key] = $valuesEn[$index];
            }
        }

        if (empty($specs) && $categoryType === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $request->input('format'),
            ];
        } elseif (empty($specs) && $categoryType === 'doc') {
            $specs = [
                'paper_type' => $request->input('paper_type'),
                'size' => $request->input('size'),
                'packaging' => $request->input('packaging'),
                'origin' => $request->input('origin'),
            ];
        }

        if (empty($specsEn) && $categoryType === 'ebooks') {
            $specsEn = [
                'pages' => $request->input('pages'),
                'language' => 'English',
                'format' => $request->input('format'),
            ];
        }

          $product->update([
              'name' => $request->name,
              'name_en' => $request->name_en,
              'slug' => $slug,
              'description' => $request->description,
              'description_en' => $request->description_en,
              'price' => $request->price,
              'price_usd' => $request->price_usd,
              'sale_price' => $request->has('is_on_sale') && $request->filled('sale_price') ? $request->sale_price : null,
              'sale_price_usd' => $request->has('is_on_sale') && $request->filled('sale_price_usd') ? $request->sale_price_usd : null,
              'category' => $categoryType,
              'category_id' => $categoryRecord->id,
              'stock' => $request->stock,
              'specs' => $specs,
              'specs_en' => $specsEn,
              'delivery_type' => $request->delivery_type,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_exclusive' => $request->has('is_exclusive') ? true : false,
            'is_combo_ai' => $request->has('is_combo_ai') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
            'is_vpn' => $request->has('is_vpn') ? true : false,
            'duration_months' => $request->duration_months,
        ]);

        // Sync features nếu có
        if ($request->has('features')) {
            $product->features()->sync($request->features);
        } else {
            $product->features()->sync([]);
        }

        // Submit to Google Indexing
        GoogleIndexingService::submitProductSafe($product, 'product_update');

        return redirect()->route('admin.products')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProduct(Product $product)
    {
        // Xóa ảnh nếu có
        if ($product->image) {
            $imagePath = parse_url($product->image, PHP_URL_PATH);
            $fullPath = PathHelper::publicRootPath($imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        
        // Xóa file nếu có
        if ($product->file_path) {
            $filePath = PathHelper::publicRootPath('files/' . $product->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function cloneProduct(Product $product)
    {
        $clone = $product->replicate();
        $clone->name = $product->name . ' (Copy)';
        $clone->slug = \Str::slug($clone->name) . '-' . time();

        // Xử lý nhân bản file hình ảnh để tránh chung file ảnh gốc bị xóa
        if ($product->image) {
            $oldImagePath = parse_url($product->image, PHP_URL_PATH);
            $oldFullImagePath = PathHelper::publicRootPath($oldImagePath);
            if (file_exists($oldFullImagePath)) {
                $extension = pathinfo($oldFullImagePath, PATHINFO_EXTENSION);
                $newImageName = time() . '_' . uniqid() . '.' . $extension;
                $newImagePath = '/images/products/' . $newImageName;
                $newFullImagePath = PathHelper::publicRootPath($newImagePath);
                
                // Copy file
                copy($oldFullImagePath, $newFullImagePath);
                $clone->image = asset($newImagePath);
            }
        }

        // Xử lý nhân bản tài liệu (file download) nếu có
        if ($product->file_path) {
            $oldFilePath = PathHelper::publicRootPath('files/' . $product->file_path);
            if (file_exists($oldFilePath)) {
                $extension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
                $filenameWithoutExt = pathinfo($product->file_path, PATHINFO_FILENAME);
                $newFileName = time() . '_' . \Str::slug($filenameWithoutExt) . '.' . $extension;
                $newFullFilePath = PathHelper::publicRootPath('files/' . $newFileName);
                
                // Copy file
                copy($oldFilePath, $newFullFilePath);
                $clone->file_path = $newFileName;
            }
        }

        $clone->save();

        // Sync features
        if ($product->features->count() > 0) {
            $clone->features()->sync($product->features->pluck('id'));
        }

        // Submit to Google Indexing
        try {
            \App\Services\GoogleIndexingService::submitProductSafe($clone, 'product_create');
        } catch (\Exception $e) {
            \Log::error('Error submitting cloned product to Google Indexing: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.edit', $clone)->with('success', 'Nhân bản sản phẩm thành công! Bạn đang chỉnh sửa bản sao.');
    }

    // Features Management
    public function features()
    {
        $features = \App\Models\Feature::latest()->paginate(20);
        return view('admin.features.index', compact('features'));
    }

    public function createFeature()
    {
        return view('admin.features.create');
    }

    public function storeFeature(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'required|in:tech,ebooks,doc',
        ]);

        \App\Models\Feature::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'icon' => $request->icon ?? 'fas fa-star',
            'color' => $request->color ?? '#667eea',
            'description' => $request->description,
            'description_en' => $request->description_en,
            'category' => $request->category,
        ]);

        return redirect()->route('admin.features')->with('success', 'Thêm tính năng thành công!');
    }

    public function editFeature(\App\Models\Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function updateFeature(Request $request, \App\Models\Feature $feature)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'required|in:tech,ebooks,doc',
        ]);

        $feature->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'icon' => $request->icon ?? 'fas fa-star',
            'color' => $request->color ?? '#667eea',
            'description' => $request->description,
            'description_en' => $request->description_en,
            'category' => $request->category,
        ]);

        return redirect()->route('admin.features')->with('success', 'Cập nhật tính năng thành công!');
    }

    public function deleteFeature(\App\Models\Feature $feature)
    {
        $feature->delete();
        return redirect()->route('admin.features')->with('success', 'Xóa tính năng thành công!');
    }

    // Product Categories Management
    public function categories(Request $request)
    {
        $query = ProductCategory::query()->withCount('products');
        
        // Search support
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->latest()->paginate(20)->appends($request->query());
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'type' => 'required|in:tech,ebooks,doc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'show_on_home' => 'nullable|boolean',
        ]);

        $slug = \Str::slug($request->name) . '-' . time();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $dir = PathHelper::publicRootPath('images/categories');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/categories/' . $fileName);

            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);

            $imagePath = '/images/categories/' . $fileName;
        }

        $category = ProductCategory::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $slug,
            'type' => $request->type,
            'image' => $imagePath ? asset($imagePath) : null,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'is_active' => $request->has('is_active'),
            'show_on_home' => $request->has('show_on_home'),
        ]);

        // Submit to Google Indexing
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/shop?category_id=' . $category->id;
            \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'category_create');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] category_create failed', [
                'category_id' => $category->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Thêm danh mục thành công!');
    }

    public function editCategory(ProductCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'type' => 'required|in:tech,ebooks,doc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'show_on_home' => 'nullable|boolean',
        ]);

        $slug = $category->slug;
        if ($request->name !== $category->name) {
            $slug = \Str::slug($request->name) . '-' . time();
        }

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            if ($category->image) {
                $oldImagePath = parse_url($category->image, PHP_URL_PATH);
                $fullPath = PathHelper::publicRootPath($oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $dir = PathHelper::publicRootPath('images/categories');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/categories/' . $fileName);

            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);

            $imagePath = asset('/images/categories/' . $fileName);
        }

        $category->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $slug,
            'type' => $request->type,
            'image' => $imagePath,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'is_active' => $request->has('is_active'),
            'show_on_home' => $request->has('show_on_home'),
        ]);

        // Submit to Google Indexing
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/shop?category_id=' . $category->id;
            \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'category_update');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] category_update failed', [
                'category_id' => $category->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function deleteCategory(ProductCategory $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Danh mục đang có sản phẩm, không thể xóa!');
        }

        if ($category->image) {
            $oldImagePath = parse_url($category->image, PHP_URL_PATH);
            $fullPath = PathHelper::publicRootPath($oldImagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Notify Google Indexing of removal before deleting
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/shop?category_id=' . $category->id;
            \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_DELETED', 'category_delete');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] category_delete failed', [
                'category_id' => $category->id,
                'error' => $e->getMessage()
            ]);
        }

        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Xóa danh mục thành công!');
    }

    // Blog Management
    public function blogs(Request $request)
    {
        $query = Blog::query();

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $blogs = $query->latest()->paginate(10);
        
        return view('admin.blogs.index', compact('blogs'));
    }

    public function createBlog()
    {
        return view('admin.blogs.create');
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:2000',
            'content' => 'required|string',
            'category' => 'required|in:tech,lifestyle,business,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'excerpt.required' => 'Tóm tắt không được để trống',
            'content.required' => 'Nội dung không được để trống',
            'category.required' => 'Danh mục không được để trống',
        ]);

        $slug = \Str::slug($request->title) . '-' . time();
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $dir = PathHelper::publicRootPath('images/blogs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/blogs/' . $fileName);
            
            // Crop ảnh về kích thước 1200x800 cho blog sắc nét
            $croppedImage = $this->cropImage($file, 1200, 800);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $imagePath = '/images/blogs/' . $fileName;
        }

        $blog = Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'image' => $imagePath ? asset($imagePath) : null,
            'is_featured' => $request->has('is_featured'),
            'user_id' => auth()->id(),
            'views' => 0,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Submit to Google Indexing
        GoogleIndexingService::submitBlogSafe($blog, 'blog_create');

        return redirect()->route('admin.blogs')->with('success', 'Thêm bài viết thành công!');
    }

    public function editBlog(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:2000',
            'content' => 'required|string',
            'category' => 'required|in:tech,lifestyle,business,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ]);

        $slug = \Str::slug($request->title) . '-' . time();
        
        $imagePath = $blog->image;
        if ($request->hasFile('image')) {
            $dir = PathHelper::publicRootPath('images/blogs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Delete old image
            if ($blog->image) {
                $oldImagePath = parse_url($blog->image, PHP_URL_PATH);
                $fullPath = PathHelper::publicRootPath($oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = PathHelper::publicRootPath('images/blogs/' . $fileName);
            // Crop ảnh về kích thước 1200x800 cho blog sắc nét
            $croppedImage = $this->cropImage($file, 1200, 800);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $imagePath = asset('/images/blogs/' . $fileName);
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category' => $request->category,
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
        ]);

        // Submit to Google Indexing
        GoogleIndexingService::submitBlogSafe($blog, 'blog_update');

        return redirect()->route('admin.blogs')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function deleteBlog(Blog $blog)
    {
        // Notify Google to remove URL before deleting
        GoogleIndexingService::removeBlogSafe($blog, 'blog_delete');

        // Delete image
        if ($blog->image) {
            $imagePath = parse_url($blog->image, PHP_URL_PATH);
            $fullPath = PathHelper::publicRootPath($imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $blog->delete();
        return redirect()->route('admin.blogs')->with('success', 'Xóa bài viết thành công!');
    }

    // Card Exchange Management
    public function cardExchanges()
    {
        $exchanges = \App\Models\CardExchange::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.card-exchanges.index', compact('exchanges'));
    }

    public function updateCardExchangeStatus(Request $request, \App\Models\CardExchange $cardExchange)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,success,failed',
            'admin_note' => 'nullable|string',
            'exchange_amount' => 'nullable|numeric|min:0',
        ]);

        $cardExchange->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'exchange_amount' => $request->exchange_amount,
            'processed_at' => now(),
        ]);

        // Gửi thông báo qua Telegram khi xử lý xong
        if ($request->status == 'success') {
            $this->sendCardExchangeSuccessNotification($cardExchange);
        }

        return redirect()->route('admin.card-exchanges')->with('success', 'Cập nhật trạng thái thành công!');
    }

    private function sendCardExchangeSuccessNotification($exchange)
    {
        $user = $exchange->user;
        
        $message = "✅ <b>ĐỔI THẺ CÀO THÀNH CÔNG</b>\n\n";
        $message .= "👤 <b>Khách hàng:</b> {$user->name}\n";
        $message .= "📧 <b>Email:</b> {$user->email}\n\n";
        $message .= "💳 <b>Thông tin thẻ:</b>\n";
        $message .= "   • Loại thẻ: {$exchange->card_type}\n";
        $message .= "   • Mệnh giá: " . number_format($exchange->card_value, 0, ',', '.') . "đ\n";
        $message .= "   • Số tiền nhận: " . number_format($exchange->exchange_amount, 0, ',', '.') . "đ\n\n";
        $message .= "🏦 <b>Ngân hàng:</b> {$exchange->bank_name}\n";
        $message .= "   • STK: {$exchange->bank_account_number}\n";
        $message .= "   • Chủ TK: {$exchange->bank_account_name}\n\n";
        $message .= "🆔 <b>Mã GD:</b> #{$exchange->id}\n";
        $message .= "🕐 <b>Xử lý lúc:</b> " . now()->format('d/m/Y H:i:s');

        \App\Helpers\TelegramHelper::sendMessage($message);
    }

    // ─── Menu Settings ───────────────────────────────────────────────────
    public function menuSettings()
    {
        return view('admin.menu-settings');
    }

    public function updateMenuSettings(Request $request)
    {
        $menuKeys = [
            'menu_home',
            'menu_shop',
            'menu_blog',
            'menu_cart',
            'menu_webdesign',
            'menu_card_exchange',
            'menu_buff',
            'menu_community',
            'menu_chat',
            'menu_minigame',
            'menu_zalo_group',
            'adsense_enabled',
        ];

        foreach ($menuKeys as $key) {
            // Checkbox: present = '1', absent = '0'
            SiteSetting::setValue($key, $request->has($key) ? '1' : '0');
        }

        // Save fake orders settings
        $settingsKeys = [
            'fake_orders_top1',
            'fake_orders_top2',
            'fake_orders_top3',
            'zalo_group_link',
            'usd_exchange_rate',
            'support_facebook_link',
            'support_zalo_link',
            'support_zalo_number',
            'support_telegram_link',
            'support_telegram_username',
            'support_phone',
            'support_email'
        ];
        foreach ($settingsKeys as $key) {
            if ($request->has($key)) {
                SiteSetting::setValue($key, $request->input($key));
            }
        }

        // Save official fanpages system settings
        if ($request->has('fanpages_submitted')) {
            $fanpages = [];
            $names = $request->input('fanpages_names', []);
            $urls = $request->input('fanpages_urls', []);
            $descs = $request->input('fanpages_descs', []);
            $platforms = $request->input('fanpages_platforms', []);
            
            foreach ($names as $index => $name) {
                if (!empty($name) && !empty($urls[$index])) {
                    $fanpages[] = [
                        'name' => $name,
                        'url' => $urls[$index],
                        'desc' => $descs[$index] ?? '',
                        'platform' => $platforms[$index] ?? 'facebook'
                    ];
                }
            }
            SiteSetting::setValue('official_fanpages', json_encode($fanpages));
        }

        return redirect()->route('admin.menu-settings')
            ->with('success', 'Đã lưu cài đặt hiển thị thành công!');
    }

    /**
     * Hiển thị trang nhập mã PIN bảo mật
     */
    public function showVerifyPin()
    {
        return view('admin.verify-pin');
    }

    /**
     * Xác thực mã PIN bảo mật
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        if ($request->pin === '113') {
            session(['admin_unlocked' => true]);
            
            // Check for previous intended URL or dashboard
            $url = session('target_url') ?? route('admin.dashboard');
            session()->forget('target_url');
            
            return redirect($url)->with('success', 'Xác thực thành công!');
        }

        return redirect()->back()->with('error', 'Mã PIN không chính xác!');
    }

    /**
     * Khóa lại toàn bộ admin (xóa session)
     */
    public function lockAdmin()
    {
        session()->forget('admin_unlocked');
        return redirect()->route('admin.dashboard')->with('success', 'Đã khóa các khu vực bảo mật.');
    }

    /**
     * Xem danh sách Email đăng ký Pre-order gom nhóm theo Keyword
     */
    public function preorders(Request $request)
    {
        // Nhóm theo keyword và đếm số lượng đăng ký
        $keywords = \App\Models\PreOrder::select('keyword', \DB::raw('count(*) as count'), \DB::raw('max(created_at) as last_activity'))
            ->groupBy('keyword')
            ->orderByDesc('last_activity')
            ->paginate(15);

        // Lấy chi tiết các lượt đăng ký (nếu có tham số filter theo keyword)
        $filterKeyword = $request->input('keyword');
        $preorders = null;
        $matchedProduct = null;
        if ($filterKeyword) {
            $preorders = \App\Models\PreOrder::where('keyword', $filterKeyword)
                ->orderByDesc('created_at')
                ->paginate(30);

            // Tìm sản phẩm khớp với keyword
            $matchedProduct = \App\Models\Product::where('slug', $filterKeyword)->first();
            if (!$matchedProduct) {
                $matchedProduct = \App\Models\Product::where('slug', 'like', '%' . $filterKeyword . '%')->first();
            }
        }

        // Lấy danh sách tất cả sản phẩm cho dropdown chọn thủ công
        $allProducts = \App\Models\Product::orderBy('name')->get();

        return view('admin.preorders.index', compact('keywords', 'preorders', 'filterKeyword', 'matchedProduct', 'allProducts'));
    }

    /**
     * Xóa lượt đăng ký pre-order
     */
    public function deletePreorder($id)
    {
        $preorder = \App\Models\PreOrder::findOrFail($id);
        $preorder->delete();
        return redirect()->back()->with('success', 'Xóa lượt đăng ký thành công!');
    }

    /**
     * Gửi email thông báo hàng đã có cho 1 lượt đăng ký pre-order
     */
    public function notifyPreorder(Request $request, $id)
    {
        $preorder = \App\Models\PreOrder::findOrFail($id);

        $product = null;
        if ($request->filled('product_id')) {
            $product = \App\Models\Product::find($request->product_id);
        }

        if (!$product) {
            // Tìm sản phẩm khớp với keyword
            $product = \App\Models\Product::where('slug', $preorder->keyword)->first();
            if (!$product) {
                $product = \App\Models\Product::where('slug', 'like', '%' . $preorder->keyword . '%')->first();
            }
        }

        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm phù hợp. Vui lòng tạo sản phẩm hoặc chọn sản phẩm thủ công.');
        }

        try {
            Mail::to($preorder->email)->send(new \App\Mail\PreOrderAvailableMail($preorder, $product));
            
            $preorder->status = 'notified';
            $preorder->save();

            return redirect()->back()->with('success', 'Đã gửi email thông báo thành công cho ' . $preorder->email . '!');
        } catch (\Exception $e) {
            \Log::error('Error sending preorder notification to ' . $preorder->email . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage());
        }
    }

    /**
     * Gửi email thông báo hàng đã có cho tất cả lượt đăng ký pre-order đang chờ của keyword
     */
    public function notifyPreordersByKeyword(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = $request->keyword;

        // Tìm các lượt đăng ký pre-order đang chờ
        $preorders = \App\Models\PreOrder::where('keyword', $keyword)
            ->where('status', 'pending')
            ->get();

        if ($preorders->isEmpty()) {
            return redirect()->back()->with('info', 'Không có khách hàng nào đang chờ thông báo cho từ khóa này.');
        }

        $product = null;
        if ($request->filled('product_id')) {
            $product = \App\Models\Product::find($request->product_id);
        }

        if (!$product) {
            // Tìm sản phẩm khớp với keyword
            $product = \App\Models\Product::where('slug', $keyword)->first();
            if (!$product) {
                $product = \App\Models\Product::where('slug', 'like', '%' . $keyword . '%')->first();
            }
        }

        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm phù hợp. Vui lòng tạo sản phẩm hoặc chọn sản phẩm thủ công.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($preorders as $preorder) {
            try {
                Mail::to($preorder->email)->send(new \App\Mail\PreOrderAvailableMail($preorder, $product));
                
                $preorder->status = 'notified';
                $preorder->save();
                
                $successCount++;
            } catch (\Exception $e) {
                \Log::error('Error sending preorder notification to ' . $preorder->email . ': ' . $e->getMessage());
                $failCount++;
            }
        }

        if ($failCount > 0) {
            return redirect()->back()->with('success', "Đã gửi email thành công tới {$successCount} khách hàng (Thất bại {$failCount}).");
        }

        return redirect()->back()->with('success', "Đã gửi email thông báo thành công tới tất cả {$successCount} khách hàng đang chờ!");
    }

    /**
     * Get sidebar counters and badges for AJAX
     */
    public function sidebarCounters()
    {
        $unreadChats = Message::where('is_admin', false)
            ->where('is_read', false)
            ->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $pendingBuffOrders = \App\Models\BuffOrder::where('status', 'paid')->count();

        $pendingCardExchanges = CardExchange::where('status', 'pending')->count();

        $abandonedCarts = AbandonedCart::where('reminder_stage', '<', 3)->count();

        $pendingPreorders = \App\Models\PreOrder::where('status', 'pending')->count();

        $pendingAffiliatesTotal = Affiliate::where('status', 'pending')->count() +
            AffiliateInvoice::where('status', 'pending')->count() +
            AffiliateWithdrawal::where('status', 'pending')->count();

        return response()->json([
            'unread_chats' => $unreadChats,
            'pending_orders' => $pendingOrders,
            'pending_buff_orders' => $pendingBuffOrders,
            'pending_card_exchanges' => $pendingCardExchanges,
            'abandoned_carts' => $abandonedCarts,
            'pending_preorders' => $pendingPreorders,
            'pending_affiliates_total' => $pendingAffiliatesTotal,
        ]);
    }



    /**
     * Danh sách các từ khóa SEO
     */
    public function seoKeywords(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\SeoKeyword::query();

        if ($search) {
            $query->where('label', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        }

        $keywords = $query->orderBy('label')->paginate(15)->withQueryString();

        return view('admin.seo-keywords.index', compact('keywords', 'search'));
    }

    /**
     * Giao diện thêm từ khóa SEO mới
     */
    public function createSeoKeyword()
    {
        return view('admin.seo-keywords.create');
    }

    /**
     * Lưu từ khóa SEO mới
     */
    public function storeSeoKeyword(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:seo_keywords,slug|alpha_dash',
            'label' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'aliases' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $aliasesStr = $request->input('aliases', '');
        $aliasesStr = str_replace(["\r\n", "\r", "\n"], ',', $aliasesStr);
        $aliases = array_values(array_filter(array_map('trim', explode(',', $aliasesStr))));

        $keyword = \App\Models\SeoKeyword::create([
            'slug' => $request->input('slug'),
            'label' => $request->input('label'),
            'heading' => $request->input('heading'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'aliases' => $aliases,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('seo_keywords_list');

        // Submit to Google Indexing
        $this->submitKeywordIndexSafe($keyword, 'keyword_create');

        return redirect()->route('admin.seo-keywords')->with('success', 'Thêm từ khóa SEO thành công!');
    }

    /**
     * Giao diện chỉnh sửa từ khóa SEO
     */
    public function editSeoKeyword($id)
    {
        $keyword = \App\Models\SeoKeyword::findOrFail($id);
        return view('admin.seo-keywords.edit', compact('keyword'));
    }

    /**
     * Cập nhật từ khóa SEO
     */
    public function updateSeoKeyword(Request $request, $id)
    {
        $keyword = \App\Models\SeoKeyword::findOrFail($id);

        $request->validate([
            'slug' => 'required|alpha_dash|unique:seo_keywords,slug,' . $keyword->id,
            'label' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'aliases' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $aliasesStr = $request->input('aliases', '');
        $aliasesStr = str_replace(["\r\n", "\r", "\n"], ',', $aliasesStr);
        $aliases = array_values(array_filter(array_map('trim', explode(',', $aliasesStr))));

        $keyword->update([
            'slug' => $request->input('slug'),
            'label' => $request->input('label'),
            'heading' => $request->input('heading'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'aliases' => $aliases,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('seo_keywords_list');

        // Submit to Google Indexing
        $this->submitKeywordIndexSafe($keyword, 'keyword_update');

        return redirect()->route('admin.seo-keywords')->with('success', 'Cập nhật từ khóa SEO thành công!');
    }

    /**
     * Xóa từ khóa SEO
     */
    public function deleteSeoKeyword($id)
    {
        $keyword = \App\Models\SeoKeyword::findOrFail($id);

        // Notify Google Indexing of removal
        $this->removeKeywordIndexSafe($keyword, 'keyword_delete');

        $keyword->delete();

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('seo_keywords_list');

        return redirect()->route('admin.seo-keywords')->with('success', 'Xóa từ khóa SEO thành công!');
    }

    /**
     * Gửi index đồng loạt cho tất cả từ khóa SEO đang hoạt động
     */
    public function submitAllKeywords(Request $request)
    {
        $keywords = \App\Models\SeoKeyword::where('is_active', true)->get();
        
        $processed = 0;
        $success = 0;
        $failed = [];
        
        $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
        
        foreach ($keywords as $k) {
            try {
                $url = $baseUrl . '/tim-kiem/' . $k->slug;
                \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'manual_bulk_keywords');
                $success++;
            } catch (\Throwable $e) {
                $failed[] = [
                    'slug' => $k->slug,
                    'message' => $e->getMessage()
                ];
            }
            $processed++;
        }
        
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => count($failed) === 0,
                'processed' => $processed,
                'submitted' => $success,
                'failed_count' => count($failed),
                'failed' => $failed,
                'message' => "Đã gửi yêu cầu Index cho {$success}/{$processed} từ khóa thành công!"
            ]);
        }
        
        return redirect()->back()->with('success', "Đã gửi yêu cầu Index cho {$success}/{$processed} từ khóa thành công!");
    }

    /**
     * Gửi index thủ công cho từ khóa SEO
     */
    public function submitKeywordIndex(Request $request, $id)
    {
        $keyword = \App\Models\SeoKeyword::findOrFail($id);
        
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/tim-kiem/' . $keyword->slug;
            
            $result = \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'manual_single_keyword');
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Gửi yêu cầu Index thành công!', 'data' => $result]);
            }
            return redirect()->back()->with('success', 'Gửi yêu cầu Index thành công!');
        } catch (\Throwable $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Lỗi khi gửi yêu cầu Index: ' . $e->getMessage());
        }
    }

    /**
     * Helper gửi index từ khóa SEO an toàn (không crash app nếu API lỗi)
     */
    private function submitKeywordIndexSafe($keyword, $source)
    {
        if ($keyword->is_active) {
            try {
                $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
                $url = $baseUrl . '/tim-kiem/' . $keyword->slug;
                
                \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', $source);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] submitKeywordIndexSafe failed', [
                    'keyword_id' => $keyword->id,
                    'slug' => $keyword->slug,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Helper thông báo xóa index từ khóa SEO an toàn (không crash app nếu API lỗi)
     */
    private function removeKeywordIndexSafe($keyword, $source)
    {
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/tim-kiem/' . $keyword->slug;
            
            \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_DELETED', $source);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] removeKeywordIndexSafe failed', [
                'keyword_id' => $keyword->id,
                'slug' => $keyword->slug,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Danh sách các chủ đề Blog
     */
    public function blogTopics(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\BlogTopic::query();

        if ($search) {
            $query->where('label', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        }

        $topics = $query->orderBy('label')->paginate(15)->withQueryString();

        return view('admin.blog-topics.index', compact('topics', 'search'));
    }

    /**
     * Giao diện thêm chủ đề Blog mới
     */
    public function createBlogTopic()
    {
        return view('admin.blog-topics.create');
    }

    /**
     * Lưu chủ đề Blog mới
     */
    public function storeBlogTopic(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:blog_topics,slug|alpha_dash',
            'label' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'aliases' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $aliasesStr = $request->input('aliases', '');
        $aliasesStr = str_replace(["\r\n", "\r", "\n"], ',', $aliasesStr);
        $aliases = array_values(array_filter(array_map('trim', explode(',', $aliasesStr))));

        $topic = \App\Models\BlogTopic::create([
            'slug' => $request->input('slug'),
            'label' => $request->input('label'),
            'heading' => $request->input('heading'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'aliases' => $aliases,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('blog_topics_list');

        // Submit to Google Indexing
        $this->submitBlogTopicIndexSafe($topic, 'blog_topic_create');

        return redirect()->route('admin.blog-topics')->with('success', 'Thêm chủ đề Blog thành công!');
    }

    /**
     * Giao diện chỉnh sửa chủ đề Blog
     */
    public function editBlogTopic($id)
    {
        $topic = \App\Models\BlogTopic::findOrFail($id);
        return view('admin.blog-topics.edit', compact('topic'));
    }

    /**
     * Cập nhật chủ đề Blog
     */
    public function updateBlogTopic(Request $request, $id)
    {
        $topic = \App\Models\BlogTopic::findOrFail($id);

        $request->validate([
            'slug' => 'required|alpha_dash|unique:blog_topics,slug,' . $topic->id,
            'label' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'aliases' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $aliasesStr = $request->input('aliases', '');
        $aliasesStr = str_replace(["\r\n", "\r", "\n"], ',', $aliasesStr);
        $aliases = array_values(array_filter(array_map('trim', explode(',', $aliasesStr))));

        $topic->update([
            'slug' => $request->input('slug'),
            'label' => $request->input('label'),
            'heading' => $request->input('heading'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'aliases' => $aliases,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('blog_topics_list');

        // Submit to Google Indexing
        $this->submitBlogTopicIndexSafe($topic, 'blog_topic_update');

        return redirect()->route('admin.blog-topics')->with('success', 'Cập nhật chủ đề Blog thành công!');
    }

    /**
     * Xóa chủ đề Blog
     */
    public function deleteBlogTopic($id)
    {
        $topic = \App\Models\BlogTopic::findOrFail($id);

        // Notify Google Indexing of removal
        $this->removeBlogTopicIndexSafe($topic, 'blog_topic_delete');

        $topic->delete();

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('blog_topics_list');

        return redirect()->route('admin.blog-topics')->with('success', 'Xóa chủ đề Blog thành công!');
    }

    /**
     * Helper gửi index chủ đề Blog an toàn
     */
    private function submitBlogTopicIndexSafe($topic, $source)
    {
        if ($topic->is_active) {
            try {
                $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
                $url = $baseUrl . '/blog/chu-de/' . $topic->slug;
                
                \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', $source);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] submitBlogTopicIndexSafe failed', [
                    'topic_id' => $topic->id,
                    'slug' => $topic->slug,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Helper thông báo xóa index chủ đề Blog an toàn
     */
    private function removeBlogTopicIndexSafe($topic, $source)
    {
        try {
            $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
            $url = $baseUrl . '/blog/chu-de/' . $topic->slug;
            
            \App\Services\GoogleIndexingService::publishUrlStatic($url, 'URL_DELETED', $source);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] removeBlogTopicIndexSafe failed', [
                'topic_id' => $topic->id,
                'slug' => $topic->slug,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
