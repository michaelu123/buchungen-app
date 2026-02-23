<?php

namespace App\Imports\RFSA;

use App\Imports\BuchungenImportBase;
use App\Models\RFSA\Buchung;

class BuchungenImport extends BuchungenImportBase
{
    protected function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
