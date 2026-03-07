<?php

namespace App\Filament\Resources\Codier\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\EditBuchungBase;
use App\Filament\Resources\Codier\Buchungen\BuchungResource;
use App\Models\Codier\Buchung;

class EditBuchung extends EditBuchungBase
{
    protected static string $resource = BuchungResource::class;

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
