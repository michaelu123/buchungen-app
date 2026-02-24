<?php

namespace App\Imports\RFSFP;

use App\Imports\KurseImportBase;
use App\Models\RFSFP\Kurs;

class KurseImport extends KurseImportBase
{
    protected function getKursModelClass(): string
    {
        return Kurs::class;
    }

    protected function getKursData($row, $note): array
    {
        return [
            'nummer' => $row['kursname'],
            'notiz' => $note,
            'uhrzeit' => $row['uhrzeit'],
            // 'kursort' => $row['kursort'],
            'datum' => $this->fromExcelDateTime($row['tag_1']),
            // 'ersatztermin' => $this->fromExcelDateTime($row['ersatztermin']),
            'ersatztermin' => "",
            'kursplätze' => $row['kursplatze'],
            'restplätze' => $row['restplatze'],
            'trainer' => $row['rfl'],
            'co_trainer' => $row['co_rfl'],
            'hospitant' => $row['hospitantin'],
            'liste_verschicken' => $row['liste_verschicken_info_an_finanzen'],
            'abgesagt_am' => $row['kurs_abgesagt_am'],
            'abgesagt_wg' => $row['kurs_abgesagt_wg'],
            'status' => $row['status'],
            'kommentar' => $row['bemerkungen'],
        ];
    }
}

/*
        'nummer',
        'notiz',
        'uhrzeit',
        // 'kursort',
        'datum',
        'ersatztermin',
        'kursplätze',
        'restplätze',
        'trainer',
        'co_trainer',
        'hospitant',
        'liste_verschicken',
        'abgesagt_am',
        'abgesagt_wg',
        'status',
        "kommentar",

*/
