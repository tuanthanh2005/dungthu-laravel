<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'variant_name',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'variant_id' => 'integer',
    ];

    // Relationship với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship với Variant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
