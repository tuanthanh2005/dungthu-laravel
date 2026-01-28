<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AbandonedCart;

class AbandonedCartReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public AbandonedCart $cart;
    public int $stage;

    public function __construct(AbandonedCart $cart, int $stage)
    {
        $this->cart = $cart;
        $this->stage = $stage;
    }

    public function envelope(): Envelope
    {
        $subject = 'Giỏ hàng của bạn đang chờ bạn';
        if ($this->stage === 1) {
            $subject = 'Nhắc nhở: bạn còn hàng trong giỏ';
        } elseif ($this->stage === 2) {
            $subject = 'Giỏ hàng sắp hết hạn ưu đãi';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
            with: [
                'cart' => $this->cart,
                'stage' => $this->stage,
                'cartUrl' => route('cart.index'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
