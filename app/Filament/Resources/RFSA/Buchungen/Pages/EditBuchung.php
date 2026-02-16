<?php

namespace App\Filament\Resources\RFSA\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\EditBuchungBase;
use App\Filament\Resources\RFSA\Buchungen\BuchungResource;
use App\Models\RFSA\Buchung;

class EditBuchung extends EditBuchungBase
{
    protected static string $resource = BuchungResource::class;

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
