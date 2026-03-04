<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $statusType;

    /**
     * @param Booking $booking
     * @param string $statusType (paid, cancelled, stripe_link, bank_details)
     */
    public function __construct(Booking $booking, $statusType)
    {
        $this->booking = $booking;
        $this->statusType = $statusType;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->statusType) {
            'paid' => 'Conferma Ricezione Pagamento - Prenotazione #' . $this->booking->id,
            'cancelled' => 'Annullamento Prenotazione #' . $this->booking->id,
            'stripe_link' => 'Link di Pagamento per la tua Prenotazione #' . $this->booking->id,
            'bank_details' => 'Dati per il Bonifico - Prenotazione #' . $this->booking->id,
            'paga_in_struttura' => 'Conferma Prenotazione - Paga in Struttura #' . $this->booking->id,
            default => 'Aggiornamento Prenotazione #' . $this->booking->id,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.status_update',
        );
    }
}
