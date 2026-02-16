<?php

namespace App\Exports\RFSF;

use App\Exports\BuchungenExportBase;
use App\Models\RFSF\Kurs;

class BuchungenExport extends BuchungenExportBase
{
    public function __construct(Kurs|null $kurs)
    {
        parent::__construct($kurs, Kurs::class, \App\Models\RFSF\Buchung::class);
    }
}
