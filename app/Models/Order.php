<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'currency',
        'status',
        'order_type',
        'coupon_code',
        'discount_amount',
        'order_code',
        'delivery_account',
        'delivery_key',
        'delivery_note',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When order is completed on creation (if status is completed)
        static::created(function ($order) {
            if ($order->status === 'completed') {
                if ($order->user_id) {
                    $order->user()->increment('spin_tickets');
                }
                $order->createCustomerDurations();
            }
        });

        // When order status is updated to completed
        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->status === 'completed' && $order->getOriginal('status') !== 'completed') {
                if ($order->user_id) {
                    $order->user()->increment('spin_tickets');
                }
                $order->createCustomerDurations();
            }
        });
    }

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship với OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship với Coupon
    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }

    // Tự động xác định loại đơn hàng dựa trên sản phẩm
    public function getOrderTypeAttribute()
    {
        // Nếu đã lưu trong DB thì dùng
        if (isset($this->attributes['order_type'])) {
            return $this->attributes['order_type'];
        }

        // Tự động xác định từ order items
        $items = $this->orderItems()->with('product')->get();
        
        if ($items->isEmpty()) {
            return 'unknown';
        }

        // Kiểm tra loại sản phẩm trong đơn
        $categories = $items->pluck('product.category')->unique();
        $deliveryTypes = $items->pluck('product.delivery_type')->unique();

        // Nếu có TikTok deal
        if ($categories->contains('tiktok')) {
            return 'qr';
        }

        // Nếu có ebooks
        if ($categories->contains('ebooks')) {
            return 'document';
        }

        // Nếu có sản phẩm giao hàng vật lý
        if ($deliveryTypes->contains('physical')) {
            return 'shipping';
        }

        // Mặc định là digital
        return 'digital';
    }

    // Format tổng tiền
    public function getFormattedTotalAttribute()
    {
        $currency = $this->currency ?? 'VND';
        if ($currency === 'USD') {
            return '$' . number_format($this->total_amount, 2);
        }
        return number_format($this->total_amount, 0, ',', '.') . 'đ';
    }

    // Format tiền giảm giá
    public function getFormattedDiscountAttribute()
    {
        $currency = $this->currency ?? 'VND';
        $discount = (float) ($this->discount_amount ?? 0);
        if ($currency === 'USD') {
            return '$' . number_format($discount, 2);
        }
        return number_format($discount, 0, ',', '.') . 'đ';
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en') {
            $statuses = [
                'pending' => 'Pending',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];
            return $statuses[$this->status] ?? 'Unknown';
        }

        $statuses = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã giao hàng',
            'delivered' => 'Đã nhận hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->status] ?? 'Không xác định';
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Scope để lọc theo loại đơn hàng
    public function scopeByType($query, $type)
    {
        return $query->whereHas('orderItems.product', function($q) use ($type) {
            if ($type === 'qr') {
                $q->where('category', 'tiktok');
            } elseif ($type === 'document') {
                $q->where('category', 'ebooks');
            } elseif ($type === 'shipping') {
                $q->where('delivery_type', 'physical');
            } elseif ($type === 'digital') {
                $q->where('delivery_type', 'digital')
                  ->whereNotIn('category', ['tiktok', 'ebooks']);
            }
        });
    }

    /**
     * Tự động tạo thời hạn cho khách hàng khi đơn hàng hoàn thành.
     */
    public function createCustomerDurations()
    {
        // Tránh lỗi nếu orderItems chưa load
        $this->loadMissing('orderItems.product');
        
        foreach ($this->orderItems as $item) {
            $exists = CustomerDuration::where('order_id', $this->id)
                ->where('product_id', $item->product_id)
                ->exists();

            if (!$exists) {
                $product = $item->product;
                $startDate = now();
                $expiryDate = null;
                $totalDuration = null;

                // Nếu sản phẩm có thời hạn → tính expiry_date
                if ($product && $product->duration_months) {
                    $expiryDate = $startDate->copy()->addMonths($product->duration_months);
                    $totalDuration = $product->duration_months . ' tháng';
                }

                $duration = CustomerDuration::create([
                    'order_id' => $this->id,
                    'order_code' => $this->order_code ?? ('DH' . $this->id),
                    'user_id' => $this->user_id,
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'product_id' => $item->product_id,
                    'product_name' => optional($product)->name ?? 'Sản phẩm #' . $item->product_id,
                    'total_duration' => $totalDuration,
                    'start_date' => $startDate,
                    'expiry_date' => $expiryDate,
                ]);

                // Gửi Telegram thông báo cho admin
                if ($expiryDate) {
                    $this->sendDurationTelegramNotification($duration, $product);
                }
            }
        }
    }

    /**
     * Gửi thông báo Telegram khi tạo thời hạn dịch vụ mới.
     */
    private function sendDurationTelegramNotification($duration, $product)
    {
        try {
            $message = "📋 <b>THỜI HẠN DỊCH VỤ MỚI</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "👤 <b>Khách hàng:</b> " . $duration->customer_name . "\n";
            $message .= "📧 <b>Email:</b> " . $duration->customer_email . "\n";
            $message .= "📱 <b>SĐT:</b> " . ($duration->customer_phone ?? 'N/A') . "\n\n";
            $message .= "📦 <b>Sản phẩm:</b> " . $duration->product_name . "\n";
            $message .= "🔖 <b>Mã đơn:</b> " . $duration->order_code . "\n";
            $message .= "⏱ <b>Thời hạn:</b> " . $duration->total_duration . "\n";
            $message .= "📅 <b>Bắt đầu:</b> " . $duration->start_date->format('d/m/Y') . "\n";
            $message .= "📅 <b>Hết hạn:</b> " . $duration->expiry_date->format('d/m/Y') . "\n\n";
            $message .= "✅ <i>Đã cấp phát thời hạn dịch vụ tự động!</i>";

            \App\Helpers\TelegramHelper::sendMessage($message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Telegram duration notification error: ' . $e->getMessage());
        }
    }
}
