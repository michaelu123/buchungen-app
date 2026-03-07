<?php

namespace App\Imports\RFSF;

use App\Imports\BuchungenImportBase;
use App\Models\RFSF\Buchung;
use App\Models\RFSF\Kurs;

class BuchungenImport extends BuchungenImportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class, Buchung::class);
    }
}
