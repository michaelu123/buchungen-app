<?php

namespace App\Mail\RFSFP;

use App\Mail\BestaetigungBase;
use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;

class Bestaetigung extends BestaetigungBase
{
    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
    }

    protected function viewName(): string
    {
        return 'mail.rfsfp.bestaetigung';
    }

    protected function attachmentPaths(): array
    {
        return glob(storage_path('app/private/mail-attachments/RFSFP/*')) ?: [];
    }
}
