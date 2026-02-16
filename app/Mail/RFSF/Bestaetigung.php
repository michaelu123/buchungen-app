<?php

namespace App\Mail\RFSF;

use App\Mail\BestaetigungBase;
use App\Models\RFSF\Kurs;
use App\Models\RFSF\Buchung;

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

    protected function fromAddress(): string
    {
        return 'radfahrschule_anmeldungen@adfc-muenchen.de';
    }

    protected function attachmentPaths(): array
    {
        return glob(app_path('Mail/RFSF/Anhaenge/*')) ?: [];
    }
}
