<?php

namespace App\Exports\Codier;

use App\Exports\BuchungenExportBase;
use App\Models\Codier\Termin;

class BuchungenExport extends BuchungenExportBase
{
    public function __construct(Termin|null $termin)
    {
        parent::__construct($termin, Termin::class, \App\Models\Codier\Buchung::class);
    }


    public function map($buchung): array
    {
        return [
            $buchung->created_at,
            $buchung->notiz,
            $buchung->termin->datum,
            $buchung->termin->beginn,
            $buchung->uhrzeit,
            $buchung->email,
            $buchung->mitgliedsnummer,
            $buchung->anrede,
            $buchung->vorname,
            $buchung->nachname,
            $buchung->postleitzahl,
            $buchung->ort,
            $buchung->strasse,
            $buchung->hsnr,
            $buchung->telefonnr,
            $buchung->anmeldebestätigung,
            $buchung->kommentar,
        ];
    }

    public function headings(): array
    {
        return [
            'Zeitstempel',
            'Notiz',
            'Datum',
            'Beginn',
            'Uhrzeit',
            'Email',
            'Mitgliedsnummer',
            'Anrede',
            'Vorname',
            'Nachname',
            'Postleitzahl',
            'Ort',
            'Strasse',
            'Hsnr',
            'Telefonnr',
            'Anmeldebestätigung',
            'Kommentar',
        ];
    }

}
