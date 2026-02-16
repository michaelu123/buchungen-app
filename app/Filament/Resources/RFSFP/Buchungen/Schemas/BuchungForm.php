<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;

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
