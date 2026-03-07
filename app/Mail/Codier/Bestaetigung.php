<?php

namespace App\Mail\Codier;

use App\Mail\BestaetigungBase;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;

class Bestaetigung extends BestaetigungBase
{
    public function __construct(public Termin $termin, Buchung $buchung)
    {
        parent::__construct($termin, $buchung);
    }

    protected function viewName(): string
    {
        return 'mail.codier.bestaetigung';
    }
}
