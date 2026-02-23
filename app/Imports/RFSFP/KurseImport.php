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
            'tag1' => $row['tag_1'],
            'tag2' => $row['tag_2'],
            'tag3' => $row['tag_3'],
            'tag4' => $row['tag_4'],
            'tag5' => $row['tag_5'],
            'tag6' => $row['tag_6'],
            'tag7' => $row['tag_7'],
            'tag8' => $row['tag_8'],
            'ersatztermin1' => $row['ersatztermin_1'],
            'ersatztermin2' => $row['ersatztermin_2'],
            'kursplätze' => $row['kursplatze'],
            'restplätze' => $row['restplatze'],
            'lehrer' => $row['rfl'],
            'co_lehrer' => $row['co_rfl'],
            'co_lehrer2' => $row['co_rfl2'],
            'hospitant' => $row['hospitantin'],
            'hospitant2' => $row['hospitantin_2'],
            'liste_verschicken' => $row['liste_verschicken'],
            'abgesagt_am' => $row['kurs_abgesagt_am'],
            'abgesagt_wg' => $row['kurs_abgesagt_wg'],
            'status' => $row['status'],
            'kommentar' => $row['bemerkungen'],
        ];
    }
}
