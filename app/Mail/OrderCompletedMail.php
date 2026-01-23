<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $demoUsername;
    public $demoPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $demoUsername, $demoPassword = 'Cudanmangorg_1')
    {
        $this->order = $order;
        $this->demoUsername = $demoUsername;
        $this->demoPassword = $demoPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Thanh Toán Thành Công - Tài Khoản Demo Đã Được Cấp #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-completed',
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
