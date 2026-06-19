<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'price',
        'price_usd',
        'sale_price',
        'sale_price_usd',
        'image',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'category_id',
        'delivery_type',
        'stock',
        'specs',
        'specs_en',
        'is_featured',
        'is_exclusive',
        'is_combo_ai',
        'is_flash_sale',
        'is_vpn',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_price_usd' => 'decimal:2',
        'stock' => 'integer',
        'specs' => 'array',
        'specs_en' => 'array',
        'is_featured' => 'boolean',
        'is_exclusive' => 'boolean',
        'is_combo_ai' => 'boolean',
        'is_flash_sale' => 'boolean',
    ];

    public function categoryRelation()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Relationship với OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship với Comments
    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user')->latest();
    }

    // Relationship với Features
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_features');
    }

    // Scope để lọc theo category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope để lấy sản phẩm còn hàng
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope để lấy sản phẩm featured
    public function scopeFeatured($query, $limit = 6)
    {
        return $query->where('is_featured', true)->latest()->limit($limit);
    }

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

    public function getSpecsAttribute($value)
    {
        $specs = $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
        if (app()->getLocale() === 'en' && !empty($this->specs_en) && !request()->is('admin*')) {
            return is_array($this->specs_en) ? $this->specs_en : json_decode($this->specs_en, true);
        }
        return $specs;
    }

    public function getEffectivePriceAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            $salePriceUsd = $this->sale_price_usd === null ? null : (float) $this->sale_price_usd;

            if ($priceUsd <= 0) {
                // Fallback to exchange rate
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
                $salePriceUsd = $this->sale_price === null ? null : ((float) $this->sale_price / $rate);
            }

            if ($salePriceUsd === null || $salePriceUsd <= 0 || $salePriceUsd >= $priceUsd) {
                return $priceUsd;
            }

            return $salePriceUsd;
        }

        $price = (float) ($this->price ?? 0);
        $salePrice = $this->sale_price === null ? null : (float) $this->sale_price;

        if ($salePrice === null || $salePrice <= 0 || $salePrice >= $price) {
            return $price;
        }

        return $salePrice;
    }

    public function getIsOnSaleAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            if ($priceUsd <= 0) {
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
            }
            return $priceUsd > 0 && (float) $this->effective_price < $priceUsd;
        }
        $price = (float) ($this->price ?? 0);
        return $price > 0 && (float) $this->effective_price < $price;
    }

    public function getDiscountPercentAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            if ($priceUsd <= 0) {
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
            }
            $effective = (float) ($this->effective_price ?? 0);

            if ($priceUsd <= 0) {
                return 0;
            }

            return (int) round((($priceUsd - $effective) / $priceUsd) * 100);
        }

        $price = (float) ($this->price ?? 0);
        $effective = (float) ($this->effective_price ?? 0);

        if ($price <= 0) {
            return 0;
        }

        return (int) round((($price - $effective) / $price) * 100);
    }

    // Format giá tiền (giá bán thực tế)
    public function getFormattedPriceAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            return '$' . number_format((float) $this->effective_price, 2);
        }
        return number_format((float) $this->effective_price, 0, ',', '.') . 'đ';
    }

    // Format giá gốc (giá niêm yết)
    public function getFormattedOriginalPriceAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            if ($priceUsd <= 0) {
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
            }
            return '$' . number_format($priceUsd, 2);
        }
        return number_format((float) ($this->price ?? 0), 0, ',', '.') . 'đ';
    }

    // Check còn hàng
    public function isInStock()
    {
        return $this->stock > 0;
    }

    // Check user đã mua sản phẩm này chưa
    public function isPurchasedBy($userId)
    {
        return $this->orderItems()
            ->whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'completed');
            })
            ->exists();
    }

    // Format file size
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        if ($this->file_size < 1024) {
            return $this->file_size . ' KB';
        } else {
            return round($this->file_size / 1024, 2) . ' MB';
        }
    }

    // Check if product has downloadable file
    public function hasFile()
    {
        return !empty($this->file_path);
    }
}
