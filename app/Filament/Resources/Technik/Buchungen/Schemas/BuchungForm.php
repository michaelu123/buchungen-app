<?php

namespace App\Filament\Resources\Technik\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\Technik\Buchung;
use App\Models\Technik\Kurs;

class BuchungForm extends BuchungFormBase
{
    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getKursModelClass(): string
    {
        return Kurs::class;
    }
}
