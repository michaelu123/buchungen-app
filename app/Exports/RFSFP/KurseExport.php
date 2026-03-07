<?php

namespace App\Exports\RFSFP;

use App\Exports\KurseExportBase;
use App\Models\RFSFP\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class, false);
    }

    public function map($kurs): array
    {
        return [
            $kurs->nummer,
            $kurs->notiz,
            $kurs->uhrzeit,
            $kurs->datum,
            $kurs->ersatztermin,
            $kurs->kursplätze,
            $kurs->restplätze,
            $kurs->trainer,
            $kurs->co_trainer,
            $kurs->hospitant,
            $kurs->liste_verschicken,
            $kurs->abgesagt_am,
            $kurs->abgesagt_wg,
            $kurs->status,
            $kurs->kommentar,
        ];
    }

    public function headings(): array // to be overridden
    {
        return [
            "Nummer",
            "Notiz",
            "Uhrzeit",
            "Datum",
            "Ersatztermin",
            "Kursplätze",
            "Restplätze",
            "Trainer",
            "Co-Trainer",
            "Hospitant",
            "Liste verschicken",
            "Abgesagt am",
            "Abgesagt wg",
            "Status",
            "Kommentar",
        ];
    }
}
