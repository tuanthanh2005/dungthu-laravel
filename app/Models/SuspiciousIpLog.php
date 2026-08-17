<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspiciousIpLog extends Model
{
    protected $table = 'suspicious_ip_logs';

    protected $fillable = [
        'ip_address',
        'reason',
        'url',
        'user_agent',
        'status',
        'banned_until',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
    ];
}
