<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ClientCarRentalRepliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carRentalRequest;
    public $messageText;
    public $attachmentPath;
    public $attachmentName;

    /**
     * Create a new message instance.
     */
    public function __construct($carRentalRequest, $messageText, $attachmentPath = null, $attachmentName = null)
    {
        $this->carRentalRequest = $carRentalRequest;
        $this->messageText = $messageText;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentName = $attachmentName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuova risposta da ' . $this->carRentalRequest->nome . ' ' . $this->carRentalRequest->cognome . ' - Richiesta Noleggio Auto',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.client_car_rental_replied',
            with: [
                'carRentalRequest' => $this->carRentalRequest,
                'messageText' => $this->messageText,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        if ($this->attachmentPath && file_exists(storage_path('app/public/' . $this->attachmentPath))) {
            return [
                Attachment::fromPath(storage_path('app/public/' . $this->attachmentPath))
                    ->as($this->attachmentName ?? 'allegato')
            ];
        }
        return [];
    }
}
