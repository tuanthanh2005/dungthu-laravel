<?php

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
use App\Mail\OrderCompletedMail;
use App\Mail\OrderApprovedMail;
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
            'pendingAffWithdrawCount'
        ));
    }

    // User Management
    public function users(Request $request)
    {
        $users = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
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

    // Order Management
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Lọc theo loại đơn hàng
        if ($request->has('type') && $request->type !== 'all') {
            $query->byType($request->type);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);
        
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

            // Gửi thông báo nội bộ (email chi tiết demo + Telegram)
            $this->sendOrderCompletedNotifications($order);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
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
     * Gửi thông báo khi đơn hàng hoàn thành
     */
    private function sendOrderCompletedNotifications(Order $order)
    {
        try {
            // Tạo username demo từ email hoặc tên khách hàng
            $demoUsername = $this->generateDemoUsername($order);
            $demoPassword = $this->generateRandomPassword();

            // Gửi email
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(
                    new OrderCompletedMail($order, $demoUsername, $demoPassword)
                );
            }

            // Gửi thông báo Telegram
            $telegramMessage = $this->formatCompletedOrderTelegramMessage($order, $demoUsername, $demoPassword);
            TelegramHelper::sendMessage($telegramMessage);

        } catch (\Exception $e) {
            \Log::error('Error sending order completed notifications: ' . $e->getMessage());
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
     * Format thông báo Telegram cho đơn hàng completed
     */
    private function formatCompletedOrderTelegramMessage(Order $order, $demoUsername, $demoPassword)
    {
        $order->load('orderItems.product');

        $message = "✅ <b>ĐƠN HÀNG ĐÃ HOÀN THÀNH - ĐÃ CÁP TÀI KHOẢN</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Thông tin đơn hàng
        $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
        $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
        $message .= "• Tổng tiền: <b>" . number_format((float)$order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";

        // Thông tin khách hàng
        $message .= "👤 <b>KHÁCH HÀNG</b>\n";
        $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
        $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
        $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n\n";

        // Thông tin tài khoản demo
        $message .= "🔐 <b>TÀI KHOẢN DEMO ĐÃ CÁP</b>\n";
        $message .= "• Username: <code>" . $demoUsername . "</code>\n";
        $message .= "• Password: <code>" . $demoPassword . "</code>\n\n";

        // Sản phẩm
        $message .= "🛒 <b>SẢN PHẨM</b>\n";
        foreach ($order->orderItems as $index => $item) {
            $message .= ($index + 1) . ". " . ($item->product->name ?? 'N/A') . "\n";
            $message .= "   • SL: " . $item->quantity . " | Giá: " . number_format($item->price, 0, ',', '.') . "đ\n";
        }

        $message .= "\n📧 Email thông báo đã được gửi tự động!";

        return $message;
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

    public function deleteOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'Xóa đơn hàng thành công!');
    }

    // Product Management
    public function products(Request $request)
    {
        $query = Product::query()->with('categoryRelation');
        
        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter flash sale products
        if ($request->filled('flash_sale')) {
            $query->where('is_flash_sale', true);
        }
        
        $products = $query->latest()->paginate(10);
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
        
        // Use specific view for tech, generic for others
        $viewName = $category === 'tech' ? 'admin.products.create-tech' : 'admin.products.create';
        return view($viewName, compact('category', 'features', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
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
        
        // Xử lý specs theo category
        $specs = [];
        if ($categoryType === 'tech') {
            // Xử lý specs động từ spec_keys và spec_values
            $keys = $request->input('spec_keys', []);
            $values = $request->input('spec_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
                }
            }
        } elseif ($categoryType === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $fileType ?? 'PDF',
            ];
        } elseif ($categoryType === 'doc') {
            $specs = [
                'paper_type' => $request->input('paper_type'),
                'size' => $request->input('size'),
                'packaging' => $request->input('packaging'),
                'origin' => $request->input('origin'),
            ];
        }

          $product = Product::create([
              'name' => $request->name,
              'slug' => $slug,
              'description' => $request->description,
              'price' => $request->price,
              'sale_price' => $request->has('is_on_sale') && $request->filled('sale_price') ? $request->sale_price : null,
              'category' => $categoryType,
              'category_id' => $categoryRecord->id,
              'stock' => $request->stock,
              'image' => $imagePath ? asset($imagePath) : null,
              'file_path' => $filePath,
              'file_type' => $fileType,
            'file_size' => $fileSize,
            'specs' => $specs,
            'delivery_type' => $request->delivery_type,
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_exclusive' => $request->has('is_exclusive') ? true : false,
            'is_combo_ai' => $request->has('is_combo_ai') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
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
        
        // Use specific view for tech, generic for others
        $viewName = $product->category === 'tech' ? 'admin.products.edit-tech' : 'admin.products.edit';
        return view($viewName, compact('product', 'features', 'categories'));
    }

      public function updateProduct(Request $request, Product $product)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'description' => 'required|string',
              'price' => 'required|numeric|min:0',
              'sale_price' => 'nullable|numeric|min:0|lt:price',
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
        
        // Xử lý specs theo category
        $specs = [];
        if ($categoryType === 'tech') {
            // Xử lý specs động từ spec_keys và spec_values
            $keys = $request->input('spec_keys', []);
            $values = $request->input('spec_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
                }
            }
        } elseif ($categoryType === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $request->input('format'),
            ];
        } elseif ($categoryType === 'doc') {
            $specs = [
                'paper_type' => $request->input('paper_type'),
                'size' => $request->input('size'),
                'packaging' => $request->input('packaging'),
                'origin' => $request->input('origin'),
            ];
        }

          $product->update([
              'name' => $request->name,
              'slug' => $slug,
              'description' => $request->description,
              'price' => $request->price,
              'sale_price' => $request->has('is_on_sale') && $request->filled('sale_price') ? $request->sale_price : null,
              'category' => $categoryType,
              'category_id' => $categoryRecord->id,
              'stock' => $request->stock,
              'specs' => $specs,
              'delivery_type' => $request->delivery_type,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_exclusive' => $request->has('is_exclusive') ? true : false,
            'is_combo_ai' => $request->has('is_combo_ai') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
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
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'category' => 'required|in:tech,ebooks,doc',
        ]);

        \App\Models\Feature::create([
            'name' => $request->name,
            'icon' => $request->icon ?? 'fas fa-star',
            'color' => $request->color ?? '#667eea',
            'description' => $request->description,
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
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'category' => 'required|in:tech,ebooks,doc',
        ]);

        $feature->update([
            'name' => $request->name,
            'icon' => $request->icon ?? 'fas fa-star',
            'color' => $request->color ?? '#667eea',
            'description' => $request->description,
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
    public function categories()
    {
        $categories = ProductCategory::withCount('products')->latest()->paginate(20);
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
            'type' => 'required|in:tech,ebooks,doc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
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

        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'type' => $request->type,
            'image' => $imagePath ? asset($imagePath) : null,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'show_on_home' => $request->has('show_on_home'),
        ]);

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
            'type' => 'required|in:tech,ebooks,doc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
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
            'slug' => $slug,
            'type' => $request->type,
            'image' => $imagePath,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'show_on_home' => $request->has('show_on_home'),
        ]);

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
        ];

        foreach ($menuKeys as $key) {
            // Checkbox: present = '1', absent = '0'
            SiteSetting::setValue($key, $request->has($key) ? '1' : '0');
        }

        return redirect()->route('admin.menu-settings')
            ->with('success', 'Đã lưu cài đặt menu thành công!');
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
}

