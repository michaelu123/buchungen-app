<?php

namespace App\Exports\RFSF;

use App\Exports\KurseExportBase;
use App\Models\RFSF\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class);
    }
}
