<?php

namespace App\Imports\RFSA;

use App\Imports\KurseImportBase;
use App\Models\RFSA\Kurs;

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
            'tag1' => $this->fromExcelDate($row['tag_1']),
            'tag2' => $this->fromExcelDate($row['tag_2']),
            'tag3' => $this->fromExcelDate($row['tag_3']),
            'tag4' => $this->fromExcelDate($row['tag_4']),
            'tag5' => $this->fromExcelDate($row['tag_5']),
            'tag6' => $this->fromExcelDate($row['tag_6']),
            'tag7' => $this->fromExcelDate($row['tag_7']),
            'tag8' => $this->fromExcelDate($row['tag_8']),
            'ersatztermin1' => $this->fromExcelDate($row['ersatztermin_1']),
            'ersatztermin2' => $this->fromExcelDate($row['ersatztermin_2']),
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
