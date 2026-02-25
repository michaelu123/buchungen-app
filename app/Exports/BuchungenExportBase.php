<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BuchungenExportBase implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
  use Exportable;

  /**
   * $kurs may be an instance of the specific Kurs model or null
   * $kursClass and $buchungClass are the fully-qualified class names for models
   */
  public function __construct(protected ?object $kurs, protected string $kursClass, protected string $buchungClass)
  {
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event): void {
        $event->sheet->getDelegate()->setTitle('Buchungen');
      },
    ];
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if ($this->kurs) {
      return $this->kurs->buchungen()->get();
    }

    $buchungClass = $this->buchungClass;
    return $buchungClass::all();
  }

  public function map($buchung): array
  {
    return [
      $buchung->created_at,
      $buchung->notiz,
      $buchung->kursnummer,
      $buchung->email,
      $buchung->mitgliedsnummer,
      $buchung->anrede,
      $buchung->vorname,
      $buchung->nachname,
      $buchung->postleitzahl,
      $buchung->ort,
      $buchung->strasse_nr,
      $buchung->telefonnr,
      $buchung->kontoinhaber,
      $buchung->iban,
      $buchung->lastschriftok,
      $buchung->verified,
      $buchung->eingezogen,
      $buchung->betrag,
      $buchung->kommentar,
    ];
  }

  public function headings(): array
  {
    return [
      'Zeitstempel',
      'Notiz',
      'Kursnummer',
      'Email',
      'Mitgliedsnummer',
      'Anrede',
      'Vorname',
      'Nachname',
      'Postleitzahl',
      'Ort',
      'Strasse_Nr',
      'Telefonnr',
      'Kontoinhaber',
      'IBAN',
      'Lastschriftok',
      'Verified',
      'Eingezogen',
      'Betrag',
      'Kommentar',
    ];
  }
}
