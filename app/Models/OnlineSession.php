<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OnlineSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'current_url',
        'page_title',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope filter active sessions within X minutes
     */
    public function scopeActive($query, int $minutes = 5)
    {
        return $query->where('last_activity', '>=', Carbon::now()->subMinutes($minutes));
    }

    /**
     * Scope for guests only
     */
    public function scopeGuests($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope for logged-in users only
     */
    public function scopeLoggedIn($query)
    {
        return $query->whereNotNull('user_id');
    }
}
