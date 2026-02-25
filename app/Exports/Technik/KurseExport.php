<?php

namespace App\Exports\Technik;

use App\Exports\KurseExportBase;
use App\Models\Technik\Kurs;

class KurseExport extends KurseExportBase
{
    public function __construct()
    {
        parent::__construct(Kurs::class);
    }
}
