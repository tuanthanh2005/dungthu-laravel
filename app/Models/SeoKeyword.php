<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'heading',
        'title',
        'description',
        'aliases',
        'is_active',
    ];

    protected $casts = [
        'aliases' => 'array',
        'is_active' => 'boolean',
    ];
}
