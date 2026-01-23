<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_type',
        'card_serial',
        'card_code',
        'card_value',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'admin_note',
        'exchange_amount',
        'processed_at',
    ];

    protected $casts = [
        'card_value' => 'decimal:2',
        'exchange_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope lọc theo status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Get status badge color
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'success' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'success' => 'Thành công',
            'failed' => 'Thất bại',
            default => 'Không xác định'
        };
    }
}
