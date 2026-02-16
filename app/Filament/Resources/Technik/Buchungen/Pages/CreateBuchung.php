<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\CreateBuchungBase;
use App\Filament\Resources\Technik\Buchungen\BuchungResource;
use App\Models\Technik\Buchung;

class CreateBuchung extends CreateBuchungBase
{
    protected static string $resource = BuchungResource::class;

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
