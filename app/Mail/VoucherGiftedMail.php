<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Coupon;

class VoucherGiftedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $coupon;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Coupon $coupon)
    {
        $this->user = $user;
        $this->coupon = $coupon;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎁 [DUNGTHU.COM] Bạn vừa nhận được Mã Voucher giảm ' . number_format($this->coupon->value, 0, ',', '.') . 'đ!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher-gifted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
