<?php

namespace App\Exports\RFSFP;

use App\Exports\KurseExportBase;
use App\Models\RFSFP\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class);
    }
}
