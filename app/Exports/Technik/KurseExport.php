<?php

namespace App\Exports\Technik;

use App\Exports\KurseExportBase;
use App\Models\Technik\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class);
    }

    public function map($kurs): array
    {
        return [
            $kurs->nummer,
            $kurs->notiz,
            $kurs->titel,
            $kurs->datum,
            $kurs->kursplätze,
            $kurs->restplätze,
            $kurs->leiter,
            $kurs->leiter2,
            $kurs->kommentar,
        ];
    }

    public function headings(): array // to be overridden
    {
        return [
            "Nummer",
            "Notiz",
            "Titel",
            "Datum",
            "Kursplätze",
            "Restplätze",
            "Leiter",
            "Leiter2",
            "Kommentar",
        ];
    }
}
