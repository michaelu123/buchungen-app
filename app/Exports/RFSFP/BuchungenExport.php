<?php

namespace App\Exports\RFSFP;

use App\Exports\BuchungenExportBase;
use App\Models\RFSFP\Kurs;

class BuchungenExport extends BuchungenExportBase
{
    public function __construct(Kurs|null $kurs)
    {
        parent::__construct($kurs, Kurs::class, \App\Models\RFSFP\Buchung::class);
    }
}
