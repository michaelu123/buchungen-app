<?php

namespace App\Imports\Technik;

use App\Imports\KurseImportBase;
use App\Models\Technik\Kurs;

class KurseImport extends KurseImportBase
{
    protected function getKursModelClass(): string
    {
        return Kurs::class;
    }

    protected function getKursData($row, $note): array
    {
        return [
            'nummer' => $row['kursnummer'],
            'notiz' => $note,
            'titel' => $row['kurstitel'],
            'datum' => $this->fromExcelDateTime($row['kursdatum']),
            'kursplätze' => $row['kursplatze'],
            'restplätze' => $row['restplatze'],
            'leiter' => $row['kursleiter'],
            'leiter2' => $row[6],
            // 'kommentar' => $row['bemerkungen'],
        ];
    }
}
