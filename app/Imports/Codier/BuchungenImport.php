<?php

namespace App\Imports\Codier;

use App\Imports\BuchungenImportBase;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;

class BuchungenImport extends BuchungenImportBase
{
    public function __construct()
    {
        parent::__construct(Termin::class, Buchung::class);
    }
}

