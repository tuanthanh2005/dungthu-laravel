<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Affiliate extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'affiliate';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'cccd_front',
        'cccd_back',
        'cccd_number',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'balance',
        'reject_reason',
        'referral_code',
        'approved_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'approved_at' => 'datetime',
            'balance' => 'decimal:0',
        ];
    }

    // Relationships
    public function invoices()
    {
        return $this->hasMany(AffiliateInvoice::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(AffiliateWithdrawal::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Helpers
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getTotalEarnedAttribute()
    {
        return $this->invoices()->where('status', 'approved')->sum('commission');
    }

    public function getTotalWithdrawnAttribute()
    {
        return $this->withdrawals()->where('status', 'approved')->sum('amount');
    }
}
