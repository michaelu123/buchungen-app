<?php

namespace App\Exports\Technik;

use App\Exports\BuchungenExportBase;
use App\Models\Technik\Kurs;

class BuchungenExport extends BuchungenExportBase
{
    public function __construct(Kurs|null $kurs)
    {
        parent::__construct($kurs, Kurs::class, \App\Models\Technik\Buchung::class);
    }
}
