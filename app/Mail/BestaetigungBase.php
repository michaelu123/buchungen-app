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

  public function __construct(public object $kurs, public object $buchung)
  {
    $this->anrede = "Hallo " . $this->buchung->vorname . " " . $this->buchung->nachname;
    if (method_exists($this->kurs, 'kursDetails')) {
      $this->kursDetails = $this->kurs->kursDetails();
    }
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address($this->fromAddress()),
      subject: 'Kursanmeldung bestätigt',
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

  protected function fromAddress(): string
  {
    return $this->buchung->getFrom();
  }


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
