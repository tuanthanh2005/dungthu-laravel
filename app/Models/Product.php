<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'image',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'category_id',
        'delivery_type',
        'stock',
        'specs',
        'is_featured',
        'is_exclusive',
        'is_combo_ai',
        'is_flash_sale',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'specs' => 'array',
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
        return $query->where('is_featured', true)->inStock()->latest()->limit($limit);
    }

    public function getEffectivePriceAttribute()
    {
        $price = (float) ($this->price ?? 0);
        $salePrice = $this->sale_price === null ? null : (float) $this->sale_price;

        if ($salePrice === null || $salePrice <= 0 || $salePrice >= $price) {
            return $price;
        }

        return $salePrice;
    }

    public function getIsOnSaleAttribute()
    {
        $price = (float) ($this->price ?? 0);
        return $price > 0 && (float) $this->effective_price < $price;
    }

    public function getDiscountPercentAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
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
        return number_format((float) $this->effective_price, 0, ',', '.') . 'đ';
    }

    // Format giá gốc (giá niêm yết)
    public function getFormattedOriginalPriceAttribute()
    {
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
