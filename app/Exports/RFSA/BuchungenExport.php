<?php

namespace App\Exports\RFSA;

use App\Exports\BuchungenExportBase;
use App\Models\RFSA\Kurs;

class BuchungenExport extends BuchungenExportBase
{
    public function __construct(Kurs|null $kurs)
    {
        parent::__construct($kurs, Kurs::class, \App\Models\RFSA\Buchung::class);
    }
}
