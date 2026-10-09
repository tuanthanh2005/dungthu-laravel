<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebDesignPackage extends Model
{
    protected $fillable = [
        'name',
        'badge',
        'badge_color',
        'price',
        'features',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'integer',
        'sort_order' => 'integer',
    ];
}
