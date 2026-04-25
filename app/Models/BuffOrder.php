<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuffOrder extends Model
{
    protected $fillable = [
        'user_id',
        'buff_service_id',
        'buff_server_id',
        'order_code',
        'social_link',
        'quantity',
        'notes',
        'emotion_type',
        'base_price',
        'unit_price',
        'total_price',
        'actual_price',
        'payment_method',
        'transaction_id',
        'paid_at',
        'status',
        'started_at',
        'completed_at',
        'admin_notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buffService(): BelongsTo
    {
        return $this->belongsTo(BuffService::class);
    }

    public function buffServer(): BelongsTo
    {
        return $this->belongsTo(BuffServer::class);
    }

    public function generateOrderCode(): string
    {
        return 'BUFF-' . strtoupper(uniqid());
    }

    public function getStatusText(): string
    {
        return match($this->status) {
            'pending' => '⏳ Chờ thanh toán',
            'paid' => '✅ Đã thanh toán',
            'processing' => '⚙️ Đang buff',
            'completed' => '🎉 Hoàn thành',
            'cancelled' => '❌ Đã hủy',
            'refunded' => '💰 Hoàn tiền',
            default => 'Không xác định'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'processing' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
            default => 'dark'
        };
    }
    public function getPriceToShow(): float
    {
        return $this->actual_price ?? $this->total_price;
    }

    public function calculateTotalPrice(): float
    {
        return ($this->unit_price * $this->quantity);
    }
}
