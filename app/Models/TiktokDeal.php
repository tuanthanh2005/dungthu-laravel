<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TiktokDeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'tiktok_link',
        'original_price',
        'sale_price',
        'discount_percent',
        'is_active',
        'is_featured',
        'order'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Scope query để chỉ lấy deals active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query để chỉ lấy deals nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope query để sắp xếp theo thứ tự
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Format giá gốc
     */
    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->original_price, 0, ',', '.') . ' ₫';
    }

    /**
     * Format giá sale
     */
    public function getFormattedSalePriceAttribute()
    {
        return number_format($this->sale_price, 0, ',', '.') . ' ₫';
    }
}
