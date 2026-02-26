<?php

namespace App\Exports\RFSA;

use App\Exports\KurseExportBase;
use App\Models\RFSA\Kurs;

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
            $kurs->uhrzeit,
            $kurs->kursort,
            $kurs->tag1,
            $kurs->tag2,
            $kurs->tag3,
            $kurs->tag4,
            $kurs->tag5,
            $kurs->tag6,
            $kurs->tag7,
            $kurs->tag8,
            $kurs->ersatztermin1,
            $kurs->ersatztermin2,
            $kurs->kursplätze,
            $kurs->restplätze,
            $kurs->lehrer,
            $kurs->co_lehrer,
            $kurs->co_lehrer2,
            $kurs->hospitant1,
            $kurs->hospitant2,
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
            "Kursort",
            "Tag1",
            "Tag2",
            "Tag3",
            "Tag4",
            "Tag5",
            "Tag6",
            "Tag7",
            "Tag8",
            "Ersatztermin1",
            "Ersatztermin2",
            "Kursplätze",
            "Restplätze",
            "Lehrer",
            "Co-Lehrer",
            "Hospitant1",
            "Hospitant2",
            "Liste verschicken",
            "Abgesagt am",
            "Abgesagt wg",
            "Status",
            "Kommentar",
        ];
    }
}
