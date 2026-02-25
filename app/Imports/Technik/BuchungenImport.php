<?php

namespace App\Imports\Technik;

use App\Imports\BuchungenImportBase;
use App\Models\Technik\Buchung;

class BuchungenImport extends BuchungenImportBase
{
    protected function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected function transformRow(array $rowData)
    {
        $kursnummer = $rowData["welche_kurse_mochtest_du_belegen"];
        $x = strpos($kursnummer, ':');
        $kursnummer = substr($kursnummer, 0, $x);
        $rowData['welchen_kurs_mochten_sie_belegen'] = $kursnummer;
        $rowData['lastschrift_name_des_kontoinhabers'] = $rowData["name_des_kontoinhabers"];
        $rowData['lastschrift_iban_kontonummer'] = $rowData["iban_kontonummer"];
        return $rowData;
    }
}

