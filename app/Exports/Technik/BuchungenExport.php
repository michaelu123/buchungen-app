<?php

namespace App\Exports\Technik;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;

class BuchungenExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->kurs) {
            return $this->kurs->buchungen()->get();
        }
        return Buchung::all();
    }

    use Exportable;

    public function __construct(public Kurs|null $kurs)
    {
    }

    public function map($buchung): array
    {
        return [
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
            $buchung->verified ? "Ja" : "Nein",
        ];
    }

    public function headings(): array
    {
        return [
            'Notiz',
            'Kurs',
            'Email',
            'Mitgliedsnummer',
            'Anrede',
            'Vorname',
            'Nachname',
            'Postleitzahl',
            'Ort',
            'Strasse Nr',
            'Telefonnummer',
            'Verifiziert',
        ];
    }

}
