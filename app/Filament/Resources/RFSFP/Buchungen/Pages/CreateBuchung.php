<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\CreateBuchungBase;
use App\Filament\Resources\RFSFP\Buchungen\BuchungResource;
use App\Models\RFSFP\Buchung;

class CreateBuchung extends CreateBuchungBase
{
    protected static string $resource = BuchungResource::class;

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
