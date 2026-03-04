<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuffService extends Model
{
    protected $fillable = [
        'name',
        'platform',
        'service_type',
        'description',
        'base_price',
        'price_per_unit',
        'min_amount',
        'max_amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serverPrices(): HasMany
    {
        return $this->hasMany(BuffServerPrice::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(BuffOrder::class);
    }

    public function getPriceForServer($serverId)
    {
        return $this->serverPrices()
            ->where('buff_server_id', $serverId)
            ->first()?->price ?? $this->price_per_unit;
    }

    public function getIcon(): string
    {
        return match($this->platform) {
            'facebook' => 'fab fa-facebook text-primary',
            'tiktok' => 'fab fa-tiktok text-dark',
            'instagram' => 'fab fa-instagram text-danger',
            default => 'fas fa-star'
        };
    }
}
