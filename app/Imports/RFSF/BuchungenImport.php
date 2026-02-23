<?php

namespace App\Imports\RFSF;

use App\Imports\BuchungenImportBase;
use App\Models\RFSF\Buchung;

class BuchungenImport extends BuchungenImportBase
{
    protected function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
