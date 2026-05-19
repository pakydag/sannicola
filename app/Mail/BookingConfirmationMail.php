<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    public $booking;
    public $isPending;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $isPending = false, $locale = null)
    {
        $this->booking = $booking;
        $this->isPending = $isPending;
        $this->locale = $locale ?? app()->getLocale();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->locale === 'en') {
            $subject = $this->isPending 
                ? 'Pending Payment - Booking #' . $this->booking->id 
                : 'Booking Confirmation #' . $this->booking->id;
        } else {
            $subject = $this->isPending 
                ? 'In attesa di pagamento - Prenotazione #' . $this->booking->id 
                : 'Conferma Prenotazione #' . $this->booking->id;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_confirmation',
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
