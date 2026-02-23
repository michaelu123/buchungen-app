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
}
