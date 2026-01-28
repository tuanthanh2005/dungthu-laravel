<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbandonedCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'cart_data',
        'items_count',
        'total_amount',
        'last_activity_at',
        'reminder_stage',
        'last_reminder_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'last_activity_at' => 'datetime',
        'last_reminder_at' => 'datetime',
        'items_count' => 'integer',
        'total_amount' => 'decimal:2',
        'reminder_stage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
