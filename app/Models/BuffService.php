<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuffService extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'platform',
        'service_type',
        'description',
        'description_en',
        'base_price',
        'price_per_unit',
        'min_amount',
        'max_amount',
        'is_active',
    ];

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en) && !request()->is('admin*')) {
            return $this->name_en;
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->description_en) && !request()->is('admin*')) {
            return $this->description_en;
        }
        return $value;
    }

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
