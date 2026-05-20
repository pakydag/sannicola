<?php

namespace App\Mail;

use App\Models\BookingCustomer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $tempPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(BookingCustomer $customer, $tempPassword)
    {
        $this->customer = $customer;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuova Password Temporanea - Area Booking',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.reset_password',
        );
    }
}
