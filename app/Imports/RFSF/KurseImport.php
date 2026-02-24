<?php

namespace App\Imports\RFSF;

use App\Imports\KurseImportBase;
use App\Models\RFSF\Kurs;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            'kursort' => $row['kursort'],
            'datum' => $this->fromExcelDateTime($row['datum']),
            'ersatztermin' => $this->fromExcelDateTime($row['ersatztermin']),
            'kursplätze' => $row['kursplatze'],
            'restplätze' => $row['restplatze'] ?? $row['kursplatze'],
            'lehrer' => $row['trainerin'],
            'co_lehrer' => $row['co_trainerin'],
            'hospitant' => $row['hospitantin'],
            'liste_verschicken' => $row['kursliste_verschicken_info_abbuchung'],
            'abgesagt_am' => $row['kurs_abgesagt_am'],
            'abgesagt_wg' => $row['kurs_abgesagt_wg'],
            'status' => $row['status'] ?? "",
            'kommentar' => $row['bemerkungen'] ?? "",
        ];
    }
}
/*
array:28 [▼ // app\Imports\RFSF\KurseImport.php:17
  "kursname" => "FaSi_01S"
  "datum" => 46115.0
  "ersatztermin" => 46129.0
  "uhrzeit" => "13:00 - 16:00"
  "kursort" => "Theresienwiese"
  "kursplatze" => 10.0
  "restplatze" => null
  "teilnehmende" => null
  "trainerin" => null
  "co_trainerin" => null
  "hospitantin" => null
  "bemerkungen" => null
  "kursliste_verschicken_info_abbuchung" => null
  "kurs_abgesagt_am" => null
  "kurs_abgesagt_wg" => null






        'nummer',
        'notiz',
        'uhrzeit',
        'kursort',
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
