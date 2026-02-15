<?php

namespace App\Mail\RFSA;

use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;

class Bestaetigung extends Mailable
{
    use Queueable, SerializesModels;

    public string $anrede, $kursDetails;
    /**
     * Create a new message instance.
     */
    public function __construct(public Kurs $kurs, public Buchung $buchung)
    {
        $this->anrede = "Liebe(r) " . /*$buchung->anrede .*/ " " . $buchung->vorname . " " . $buchung->nachname;
        $this->kursDetails = $kurs->kursDetails();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kursanmeldung bestätigt',
            from: new Address("radfahrschule_anmeldungen@adfc-muenchen.de"),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.rfsa.bestaetigung',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];// TODO
    }
}
