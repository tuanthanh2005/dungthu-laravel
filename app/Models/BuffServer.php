<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuffServer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_maintenance',
    ];

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
