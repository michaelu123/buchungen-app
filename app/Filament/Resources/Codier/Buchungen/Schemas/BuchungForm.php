<?php

namespace App\Filament\Resources\Codier\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;

class BuchungForm extends BuchungFormBase
{
    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getKursModelClass(): string
    {
        return Termin::class;
    }
}
