<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category',
        'is_featured',
        'user_id',
        'views',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope để lấy bài đã publish
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Scope lọc theo category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope lấy bài nổi bật
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope lấy bài mới nhất
    public function scopeLatestPosts($query, $limit = 5)
    {
        return $query->published()->orderBy('published_at', 'desc')->limit($limit);
    }

    // Tăng view count
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Format ngày tháng
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y') : $this->created_at->format('d/m/Y');
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (preg_match('/^(https?:)?\\/\\//i', $value)) {
            return $value;
        }

        if ($value[0] !== '/') {
            $value = '/' . $value;
        }

        return asset($value);
    }
}
