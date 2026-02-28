<?php

namespace App\Mail\Technik;

use App\Mail\BestaetigungBase;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;

class Bestaetigung extends BestaetigungBase
{
    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
    }

    protected function viewName(): string
    {
        return 'mail.technik.bestaetigung';
    }

    protected function fromAddress(): string
    {
        return 'technik_anmeldungen@adfc-muenchen.de';
    }
}
