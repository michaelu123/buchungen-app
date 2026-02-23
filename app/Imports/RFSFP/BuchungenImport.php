<?php

namespace App\Imports\RFSFP;

use App\Imports\BuchungenImportBase;
use App\Models\RFSFP\Buchung;

class BuchungenImport extends BuchungenImportBase
{
    protected function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
