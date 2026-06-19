<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuffServer extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'is_active',
        'is_maintenance',
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
        'is_maintenance' => 'boolean',
    ];

    public function serverPrices(): HasMany
    {
        return $this->hasMany(BuffServerPrice::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(BuffOrder::class);
    }

    public function getStatusText(): string
    {
        if (!$this->is_active) {
            return '❌ Không hoạt động';
        }
        if ($this->is_maintenance) {
            return '🔧 Bảo trì';
        }
        return '✅ Sẵn sàng';
    }

    public function getStatusColor(): string
    {
        if (!$this->is_active) {
            return 'danger';
        }
        if ($this->is_maintenance) {
            return 'warning';
        }
        return 'success';
    }
}
