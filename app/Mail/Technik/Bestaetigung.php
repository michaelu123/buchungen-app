<?php

namespace App\Mail\Technik;

use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;

class Bestaetigung extends Mailable
{
    use Queueable, SerializesModels;

    public string $anrede;
    /**
     * Create a new message instance.
     */
    public function __construct(public Kurs $kurs, public Buchung $buchung)
    {
        $this->anrede = "Liebe(r) " . /*$buchung->anrede .*/ " " . $buchung->vorname . " " . $buchung->nachname;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kursanmeldung bestätigt',
            from: new Address("technik_anmeldungen@adfc-muenchen.de"),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.technik.bestätigung',
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
