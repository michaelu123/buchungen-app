<?php

namespace App\Imports\Technik;

use App\Imports\BuchungenImportBase;
use App\Models\Technik\Buchung;
use App\Models\Technik\Kurs;

class BuchungenImport extends BuchungenImportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class, Buchung::class);
    }

    protected function transformRow(array $rowData): array
    {
        $rowData = parent::transformRow($rowData);
        $kursnummer = $rowData["welche_kurse_mochtest_du_belegen"];
        $x = strpos($kursnummer, ':');
        $kursnummer = substr($kursnummer, 0, $x);
        $rowData['welchen_kurs_mochten_sie_belegen'] = $kursnummer;
        $rowData['lastschrift_name_des_kontoinhabers'] = $rowData["name_des_kontoinhabers"];
        $rowData['lastschrift_iban_kontonummer'] = $rowData["iban_kontonummer"];
        $rowData['bemerkung'] = $rowData["kommentar"];
        if (str_starts_with(strtoupper($rowData["eingezogen"]), "AKTIV")) {
            $rowData["eingezogen"] = "1999-01-01";
            $rowData['lastschrift_iban_kontonummer'] = "Aktiver";
        }
        return $rowData;
    }
}

