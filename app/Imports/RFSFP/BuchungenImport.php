<?php

namespace App\Imports\RFSFP;

use App\Imports\BuchungenImportBase;
use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;

class BuchungenImport extends BuchungenImportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class, Buchung::class);
    }
}
