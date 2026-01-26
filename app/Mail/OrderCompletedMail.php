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
        $publicOrderNumber = $this->getPublicOrderNumber();

        return new Envelope(
            subject: '✅ Đơn #' . $publicOrderNumber . ' đã xác nhận – Tài khoản demo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $publicOrderNumber = $this->getPublicOrderNumber();

        return new Content(
            view: 'emails.order-completed',
            with: [
                'publicOrderNumber' => $publicOrderNumber,
            ],
        );
    }

    private function getPublicOrderNumber(): int
    {
        $start = (int) config('admin.public_order_start', 1);
        $start = max(1, $start);

        $id = (int) ($this->order->id ?? 0);
        $id = max(0, $id);

        return $id + ($start - 1);
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
