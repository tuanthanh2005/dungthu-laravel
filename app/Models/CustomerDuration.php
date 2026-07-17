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
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
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
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>', now()->addDays(3));
        });
    }

    public function scopeExpiring($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now()->startOfDay())
            ->where('expiry_date', '<=', now()->addDays(3)->endOfDay());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->startOfDay());
    }

    // Accessors
    public function getStatusAttribute()
    {
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
