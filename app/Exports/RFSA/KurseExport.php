<?php

namespace App\Exports\RFSA;

use App\Exports\KurseExportBase;
use App\Models\RFSA\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class);
    }
}
