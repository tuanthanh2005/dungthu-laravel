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
        'status',
        'order_type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

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
        return number_format($this->total_amount, 0, ',', '.') . 'đ';
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending_approval' => 'Chờ duyệt',
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
            'pending_approval' => 'warning',
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
}
