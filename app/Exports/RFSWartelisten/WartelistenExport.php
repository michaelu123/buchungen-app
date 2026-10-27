<?php

namespace App\Exports\RFSWartelisten;

use App\Models\RFSWartelisten\Warteliste;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class WartelistenExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $event->sheet->getDelegate()->setTitle('Wartelisten');
            },
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Warteliste::all();
    }

    public function map($wl): array
    {
        return [
            $wl->created_at,
            $wl->kursart,
            $wl->email,
            $wl->anrede,
            $wl->vorname,
            $wl->nachname,
            $wl->telefonnr,
            $wl->mitteilung,
            $wl->kommentar,
        ];
    }

    public function headings(): array
    {
        return [
            'Zeitstempel',
            'Kursart',
            'Email',
            'Anrede',
            'Vorname',
            'Nachname',
            'Telefonnr',
            'Mitteilung',
            'Kommentar',
        ];
    }
}
