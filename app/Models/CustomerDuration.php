<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerDuration extends Model
{
    protected $fillable = [
        'order_id',
        'order_code',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'product_id',
        'product_name',
        'total_duration',
        'start_date',
        'expiry_date',
        'is_completed',
        'admin_note',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_completed', false)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>', now()->addDays(3));
            });
    }

    public function scopeExpiring($query)
    {
        return $query->where('is_completed', false)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now()->startOfDay())
            ->where('expiry_date', '<=', now()->addDays(3)->endOfDay());
    }

    public function scopeExpired($query)
    {
        return $query->where('is_completed', false)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->startOfDay());
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->is_completed) {
            return 'completed';
        }

        if (is_null($this->expiry_date)) {
            return 'active';
        }

        $now = now()->startOfDay();
        $expiry = Carbon::parse($this->expiry_date)->startOfDay();

        if ($expiry->isPast()) {
            return 'expired';
        }

        $daysLeft = $now->diffInDays($expiry, false);
        if ($daysLeft >= 0 && $daysLeft <= 3) {
            return 'expiring';
        }

        return 'active';
    }

    public function getStatusLabelAttribute()
    {
        $status = $this->status;
        if ($status === 'completed') {
            return 'Đã hoàn thành';
        }
        if ($status === 'expired') {
            return 'Đã hết hạn';
        }
        if ($status === 'expiring') {
            return 'Sắp hết hạn';
        }
        return 'Đang hoạt động';
    }

    public function getStatusColorAttribute()
    {
        $status = $this->status;
        if ($status === 'completed') {
            return 'secondary';
        }
        if ($status === 'expired') {
            return 'danger';
        }
        if ($status === 'expiring') {
            return 'warning';
        }
        return 'success';
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->is_completed) {
            return 'Đã hoàn thành';
        }

        if (is_null($this->expiry_date)) {
            return 'Chưa thiết lập';
        }

        $now = now()->startOfDay();
        $expiry = Carbon::parse($this->expiry_date)->startOfDay();

        if ($expiry->isPast()) {
            return 'Đã hết hạn';
        }

        $daysLeft = $now->diffInDays($expiry, false);
        if ($daysLeft === 0) {
            return 'Hết hạn hôm nay';
        }

        return 'Còn ' . $daysLeft . ' ngày';
    }
}
