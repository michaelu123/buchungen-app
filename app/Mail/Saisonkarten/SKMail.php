<?php

namespace App\Mail\Saisonkarten;

use App\Models\Saisonkarten\BasisDaten;
use App\Models\Saisonkarten\Buchung;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class SKMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $encNr;

    public function __construct(public Buchung $buchung, public BasisDaten $basisdaten)
    {
        $this->encNr = Crypt::encryptString(implode(",", [$buchung->sknummer, $basisdaten->jahr]));
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
        return [
            Attachment::fromPath($this->buchung->pngPath),
            Attachment::fromPath($this->buchung->jpgPath),
            Attachment::fromPath($this->buchung->pdfPath),
        ];
    }
}
