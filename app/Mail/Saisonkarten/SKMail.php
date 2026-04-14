<?php

namespace App\Mail\Saisonkarten;

use App\Models\Saisonkarten\Buchung;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SKMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Buchung $buchung)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->buchung->getFrom()),
            subject: 'Ihre Saisonkarte',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.saisonkarten.skmail',
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
