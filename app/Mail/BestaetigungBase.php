<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;

abstract class BestaetigungBase extends Mailable
{
  use Queueable, SerializesModels;

  public string $anrede;
  public ?string $kursDetails = null;

  public function __construct(protected object $kurs, protected object $buchung)
  {
    $this->anrede = "Liebe(r) " . " " . $this->buchung->vorname . " " . $this->buchung->nachname;
    if (method_exists($this->kurs, 'kursDetails')) {
      $this->kursDetails = $this->kurs->kursDetails();
    }
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Kursanmeldung bestätigt',
      from: new Address($this->fromAddress()),
    );
  }

  public function content(): Content
  {
    return new Content(
      view: $this->viewName(),
    );
  }

  /**
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    $paths = $this->attachmentPaths();
    $paths = $paths ?: [];
    return array_map(fn(string $p) => Attachment::fromPath($p), $paths);
  }

  abstract protected function viewName(): string;

  abstract protected function fromAddress(): string;

  /**
   * Return filesystem paths to attachment files.
   *
   * @return string[]
   */
  protected function attachmentPaths(): array
  {
    return [];
  }
}
