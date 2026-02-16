<?php

namespace App\Mail\RFSFP;

use App\Mail\BestaetigungBase;
use App\Models\RFSFP\Kurs;
use App\Models\RFSFP\Buchung;

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

    protected function fromAddress(): string
    {
        return 'radfahrschule_anmeldungen@adfc-muenchen.de';
    }

    protected function attachmentPaths(): array
    {
        return glob(app_path('Mail/RFSFP/Anhaenge/*')) ?: [];
    }
}
