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
        'image',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'delivery_type',
        'stock',
        'specs',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'specs' => 'array',
        'is_featured' => 'boolean',
    ];

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

    // Format giá tiền
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
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
