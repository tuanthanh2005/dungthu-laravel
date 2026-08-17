<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedIp extends Model
{
    protected $table = 'banned_ips';

    protected $fillable = [
        'ip_address',
        'reason',
        'banned_by',
        'banned_until',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
    ];

    /**
     * Check if the IP ban is currently active
     */
    public function isActive(): bool
    {
        if (is_null($this->banned_until)) {
            return true;
        }

        return $this->banned_until->isFuture();
    }
}
