<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateWithdrawal extends Model
{
    protected $fillable = [
        'affiliate_id',
        'amount',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'note',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:0',
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
