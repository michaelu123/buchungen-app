<?php

namespace App\Mail\Codier;

use App\Mail\BestaetigungBase;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;
use Illuminate\Support\Facades\Crypt;

class Bestaetigung extends BestaetigungBase
{
    public string $abmeldeUrl;
    public function __construct(public Termin $termin, Buchung $buchung)
    {
        parent::__construct($termin, $buchung);
        $this->abmeldeUrl = route("codier.abmelden", ["encid" => Crypt::encryptString($buchung->id)]);
    }

    protected function viewName(): string
    {
        return 'mail.codier.bestaetigung';
    }

    protected function attachmentPaths(): array
    {
        return glob(app_path('Mail/Codier/Anhaenge/*')) ?: [];
    }

}
