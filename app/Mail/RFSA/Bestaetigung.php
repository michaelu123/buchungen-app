<?php

namespace App\Mail\RFSA;

use App\Mail\BestaetigungBase;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;

class Bestaetigung extends BestaetigungBase
{
    public string $zahlungsText;

    public function __construct(Kurs $kurs, Buchung $buchung)
    {
        parent::__construct($kurs, $buchung);
        if ($buchung->gutscheincode && $buchung->validGutscheinCode()) {
            $this->zahlungsText = 'Da Sie bei der Buchung einen gültigen Gutscheincode angegeben haben, müssen Sie nichts zahlen.';
        } else {
            $this->zahlungsText = 'Wir ziehen die Teilnahmegebühr von ' . ($buchung->ermäßigung == 'Ja' ? 40 : 120) . '€ in den nächsten Tagen ein.';
        }
    }

    protected function viewName(): string
    {
        return 'mail.rfsa.bestaetigung';
    }

    protected function attachmentPaths(): array
    {
        return glob(storage_path('app/private/mail-attachments/RFSA/*')) ?: [];
    }
}
