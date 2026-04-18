<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateInvoice extends Model
{
    protected $fillable = [
        'affiliate_id',
        'product_name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'amount',
        'commission',
        'bill_image',
        'note',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:0',
            'commission' => 'decimal:0',
            'processed_at' => 'datetime',
        ];
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            default    => 'Không xác định',
        };
    }
}
