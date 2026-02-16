<?php

namespace App\Mail\RFSA;

use App\Mail\BestaetigungBase;
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;

class Bestaetigung extends BestaetigungBase
{
    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
    }

    protected function viewName(): string
    {
        return 'mail.rfsa.bestaetigung';
    }

    protected function fromAddress(): string
    {
        return 'radfahrschule_anmeldungen@adfc-muenchen.de';
    }

    protected function attachmentPaths(): array
    {
        return glob(app_path('Mail/RFSA/Anhaenge/*')) ?: [];
    }
}
