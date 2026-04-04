<?php

namespace App\Mail\RFSA;

use App\Mail\BestaetigungBase;
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;

class Bestaetigung extends BestaetigungBase
{
    public string $zahlungsText;

    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
        $this->zahlungsText = "Wir ziehen die Teilnahmegebühr von " . ($buchung->ermäßigung == "Ja" ? 40 : 120) . "€ in den nächsten Tagen ein.";
    }

    protected function viewName(): string
    {
        return 'mail.rfsa.bestaetigung';
    }

    protected function attachmentPaths(): array
    {
        return glob(app_path('Mail/RFSA/Anhaenge/*')) ?: [];
    }
}
