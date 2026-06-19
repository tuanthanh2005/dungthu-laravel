<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'type',
        'image',
        'description',
        'description_en',
        'is_active',
        'show_on_home',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_home' => 'boolean',
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

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
