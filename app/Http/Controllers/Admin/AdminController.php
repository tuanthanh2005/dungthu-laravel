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
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;
use App\Helpers\TelegramHelper;

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
        
        return view('admin.dashboard', compact(
            'stats', 
            'latestOrders', 
            'unreadChatCount',
            'pendingOrdersCount',
            'pendingCardExchangeCount',
            'abandonedCartsCount'
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

        // Nếu đơn hàng được chuyển sang trạng thái completed, gửi email và telegram
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $this->sendOrderCompletedNotifications($order);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
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
        $message .= "• Tổng tiền: <b>" . number_format($order->total_amount, 0, ',', '.') . "đ</b>\n";
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

    public function deleteOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'Xóa đơn hàng thành công!');
    }

    // Product Management
    public function products(Request $request)
    {
        $query = Product::query();
        
        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        $products = $query->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct($category = null)
    {
        if ($category && !in_array($category, ['tech', 'ebooks', 'doc'])) {
            abort(404);
        }
        
        // Lấy danh sách features theo category
        $features = \App\Models\Feature::when($category, function($query) use ($category) {
            return $query->where('category', $category);
        })->get();
        
        // Use specific view for tech, generic for others
        $viewName = $category === 'tech' ? 'admin.products.create-tech' : 'admin.products.create';
        return view($viewName, compact('category', 'features'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category' => 'required|in:tech,ebooks,doc',
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
              'category.required' => 'Danh mục không được để trống',
              'category.in' => 'Danh mục không hợp lệ',
              'stock.required' => 'Số lượng không được để trống',
              'stock.integer' => 'Số lượng phải là số nguyên',
              'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'file.mimes' => 'File phải có định dạng: pdf, doc, docx, txt, zip, rar',
            'file.max' => 'Kích thước file không được vượt quá 50MB',
        ]);

        $slug = \Str::slug($request->name) . '-' . time();
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = public_path('images/products/' . $fileName);
            
            // Crop ảnh về kích thước 500x334
            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $imagePath = '/images/products/' . $fileName;
        }
        
        // Xử lý file upload cho ebooks
        $filePath = null;
        $fileType = null;
        $fileSize = null;
        
        if ($request->hasFile('file') && $request->category === 'ebooks') {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . \Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Lấy size trước khi move
            $fileSize = round($file->getSize() / 1024); // Convert to KB
            
            // Lưu file vào public/files
            $file->move(public_path('files'), $fileName);
            
            $filePath = $fileName;
            $fileType = $extension;
        }
        
        // Xử lý specs theo category
        $specs = [];
        if ($request->category === 'tech') {
            // Xử lý specs động từ spec_keys và spec_values
            $keys = $request->input('spec_keys', []);
            $values = $request->input('spec_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
                }
            }
        } elseif ($request->category === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $fileType ?? 'PDF',
            ];
        } elseif ($request->category === 'doc') {
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
              'category' => $request->category,
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

        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function editProduct(Product $product)
    {
        // Lấy danh sách features theo category của product
        $features = \App\Models\Feature::where('category', $product->category)->get();
        
        // Use specific view for tech, generic for others
        $viewName = $product->category === 'tech' ? 'admin.products.edit-tech' : 'admin.products.edit';
        return view($viewName, compact('product', 'features'));
    }

      public function updateProduct(Request $request, Product $product)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'description' => 'required|string',
              'price' => 'required|numeric|min:0',
              'sale_price' => 'nullable|numeric|min:0|lt:price',
              'category' => 'required|in:tech,ebooks,doc',
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
              'category.required' => 'Danh mục không được để trống',
              'category.in' => 'Danh mục không hợp lệ',
              'stock.required' => 'Số lượng không được để trống',
              'stock.integer' => 'Số lượng phải là số nguyên',
              'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'file.mimes' => 'File phải có định dạng: pdf, doc, docx, txt, zip, rar',
            'file.max' => 'Kích thước file không được vượt quá 50MB',
        ]);

        $slug = \Str::slug($request->name) . '-' . $product->id;
        
        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                $oldImagePath = parse_url($product->image, PHP_URL_PATH);
                $fullPath = public_path($oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = public_path('images/products/' . $fileName);
            
            // Crop ảnh về kích thước 500x334
            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $product->image = asset('/images/products/' . $fileName);
        }
        
        // Xử lý upload file mới cho ebooks
        if ($request->hasFile('file') && $request->category === 'ebooks') {
            // Xóa file cũ nếu có
            if ($product->file_path) {
                $oldFilePath = public_path('files/' . $product->file_path);
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
            $file->move(public_path('files'), $fileName);
            
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
        if ($request->category === 'tech') {
            // Xử lý specs động từ spec_keys và spec_values
            $keys = $request->input('spec_keys', []);
            $values = $request->input('spec_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specs[$key] = $values[$index];
                }
            }
        } elseif ($request->category === 'ebooks') {
            $specs = [
                'pages' => $request->input('pages'),
                'language' => $request->input('language', 'Tiếng Việt'),
                'format' => $request->input('format'),
            ];
        } elseif ($request->category === 'doc') {
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
              'category' => $request->category,
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

        return redirect()->route('admin.products')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProduct(Product $product)
    {
        // Xóa ảnh nếu có
        if ($product->image) {
            $imagePath = parse_url($product->image, PHP_URL_PATH);
            $fullPath = public_path($imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        
        // Xóa file nếu có
        if ($product->file_path) {
            $filePath = public_path('files/' . $product->file_path);
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
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:tech,lifestyle,business,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'excerpt.required' => 'Tóm tắt không được để trống',
            'content.required' => 'Nội dung không được để trống',
            'category.required' => 'Danh mục không được để trống',
        ]);

        $slug = \Str::slug($request->title) . '-' . time();
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $dir = public_path('images/blogs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = public_path('images/blogs/' . $fileName);
            
            // Crop ảnh về kích thước 500x334
            $croppedImage = $this->cropImage($file);
            $this->saveImage($croppedImage, $fullPath, $extension);
            
            $imagePath = '/images/blogs/' . $fileName;
        }

        Blog::create([
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
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:tech,lifestyle,business,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = \Str::slug($request->title) . '-' . time();
        
        $imagePath = $blog->image;
        if ($request->hasFile('image')) {
            $dir = public_path('images/blogs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Delete old image
            if ($blog->image) {
                $oldImagePath = parse_url($blog->image, PHP_URL_PATH);
                $fullPath = public_path($oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $fullPath = public_path('images/blogs/' . $fileName);
            
            $croppedImage = $this->cropImage($file);
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

        return redirect()->route('admin.blogs')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function deleteBlog(Blog $blog)
    {
        // Delete image
        if ($blog->image) {
            $imagePath = parse_url($blog->image, PHP_URL_PATH);
            $fullPath = public_path($imagePath);
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
}
