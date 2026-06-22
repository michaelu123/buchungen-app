<?php

namespace App\Exports\Codier;

use App\Exports\BuchungenExportBase;
use App\Models\Codier\Termin;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BuchungenExport extends BuchungenExportBase implements WithStyles
{
    public function __construct(Termin|null $termin)
    {
        parent::__construct($termin, Termin::class, \App\Models\Codier\Buchung::class);
    }


    public function map($buchung): array
    {
        return [
            Carbon::parse($buchung->termin->datum)->translatedFormat('D, d.m.y'),
            substr($buchung->termin->beginn, 0, 5),
            $buchung->vorname,
            $buchung->nachname,
            $buchung->postleitzahl,
            $buchung->ort,
            $buchung->strasse,
            $buchung->hsnr,
            $buchung->ein,
            $buchung->telefonnr,
            $buchung->email,
            $buchung->created_at,
            $buchung->notiz,
            $buchung->uhrzeit,
            $buchung->mitgliedsnummer,
            $buchung->anrede,
            $buchung->anmeldebestätigung,
            $buchung->kommentar,
        ];
    }

    public function headings(): array
    {
        // Datum Beginn Vorname Nachname PLZ Ort Strasse Hsnr EIN Telefonnr Email
        return [
            'Datum',
            'Beginn',
            'Vorname',
            'Nachname',
            'Postleitzahl',
            'Ort',
            'Strasse',
            'Hsnr',
            'EIN',
            'Telefonnr',
            'Email',
            'Zeitstempel',
            'Notiz',
            'Uhrzeit',
            'Mitgliedsnummer',
            'Anrede',
            'Anmeldebestätigung',
            'Kommentar',
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            // 1 => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C' => ['font' => ['size' => 16]],
            1 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]
        ];
    }
}
