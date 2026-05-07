<?php

namespace App\Mail\RFSF;

use App\Mail\BestaetigungBase;
use App\Models\RFSF\Buchung;
use App\Models\RFSF\Kurs;

class Bestaetigung extends BestaetigungBase
{
    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
    }

    protected function viewName(): string
    {
        return 'mail.rfsf.bestaetigung';
    }

    protected function attachmentPaths(): array
    {
        return glob(storage_path('app/private/mail-attachments/RFSF/*')) ?: [];
    }
}
