<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'name_en',
        'price',
        'price_usd',
        'sale_price',
        'sale_price_usd',
        'stock',
        'duration_value',
        'duration_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_price_usd' => 'decimal:2',
        'stock' => 'integer',
        'duration_value' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getNameLocalizedAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en) && !request()->is('admin*')) {
            return $this->name_en;
        }
        return $this->name;
    }

    public function getEffectivePriceAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            $salePriceUsd = $this->sale_price_usd === null ? null : (float) $this->sale_price_usd;

            if ($priceUsd <= 0) {
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
                $salePriceUsd = $this->sale_price === null ? null : ((float) $this->sale_price / $rate);
            }

            if ($salePriceUsd !== null && $salePriceUsd > 0 && $salePriceUsd < $priceUsd) {
                return $salePriceUsd;
            }
            return $priceUsd;
        }

        $price = (float) ($this->price ?? 0);
        $salePrice = $this->sale_price === null ? null : (float) $this->sale_price;

        if ($salePrice !== null && $salePrice > 0 && $salePrice < $price) {
            return $salePrice;
        }

        return $price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            $priceUsd = (float) ($this->price_usd ?? 0);
            $salePriceUsd = $this->sale_price_usd === null ? null : (float) $this->sale_price_usd;

            if ($priceUsd <= 0) {
                $rate = (float) SiteSetting::getValue('usd_exchange_rate', 25000);
                $priceUsd = (float) ($this->price ?? 0) / $rate;
                $salePriceUsd = $this->sale_price === null ? null : ((float) $this->sale_price / $rate);
            }

            return $salePriceUsd !== null && $salePriceUsd > 0 && $salePriceUsd < $priceUsd;
        }

        $price = (float) ($this->price ?? 0);
        $salePrice = $this->sale_price === null ? null : (float) $this->sale_price;

        return $salePrice !== null && $salePrice > 0 && $salePrice < $price;
    }

    public function getFormattedPriceAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !request()->is('admin*')) {
            return '$' . number_format($this->effective_price, 2);
        }
        return number_format($this->effective_price, 0, ',', '.') . 'đ';
    }

    public function getFormattedOriginalPriceAttribute(): string
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

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        $price = (float) ($this->price ?? 0);
        $salePrice = (float) ($this->sale_price ?? 0);

        if ($price <= 0) {
            return 0;
        }

        return (int) round((($price - $salePrice) / $price) * 100);
    }

    public function getDurationTextAttribute(): string
    {
        if (!$this->duration_value || !$this->duration_type) {
            return '';
        }

        if ($this->duration_type === 'days') {
            return $this->duration_value . ' ngày';
        }
        if ($this->duration_type === 'months') {
            return $this->duration_value . ' tháng';
        }
        if ($this->duration_type === 'years') {
            return $this->duration_value . ' năm';
        }

        return $this->duration_value . ' ' . $this->duration_type;
    }
}
