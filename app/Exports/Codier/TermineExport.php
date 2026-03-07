<?php

namespace App\Exports\Codier;

use App\Exports\KurseExportBase;
use App\Models\Codier\Termin;

class TermineExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Termin::class, true);
    }

    public function map($termin): array
    {
        return [
            $termin->notiz,
            $termin->datum,
            $termin->beginn,
            $termin->ende,
            $termin->kommentar,
        ];
    }

    public function headings(): array // to be overridden
    {
        return [
            "Notiz",
            "Datum",
            "Beginn",
            "Ende",
            "Kommentar",
        ];
    }
}
