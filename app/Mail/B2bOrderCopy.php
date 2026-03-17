<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class B2bOrderCopy extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentLink;
    public $paymentMethod;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $paymentLink = null, $paymentMethod = 'bonifico')
    {
        $this->order = $order;
        $this->paymentLink = $paymentLink;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Riepilogo Ordine B2B #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_copy',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
