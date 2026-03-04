<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuffServerPrice extends Model
{
    protected $fillable = [
        'buff_service_id',
        'buff_server_id',
        'price',
    ];

    public function buffService(): BelongsTo
    {
        return $this->belongsTo(BuffService::class);
    }

    public function buffServer(): BelongsTo
    {
        return $this->belongsTo(BuffServer::class);
    }
}
