<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTopic extends Model
{
    use HasFactory;

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
