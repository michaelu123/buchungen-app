<?php

namespace App\Imports\RFSA;

use App\Imports\BuchungenImportBase;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;

class BuchungenImport extends BuchungenImportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class, Buchung::class);
    }
}
