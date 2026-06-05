<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'value',
        'user_id',
        'is_used',
        'used_at',
        'order_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * Get the user that owns the coupon.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that used the coupon.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
