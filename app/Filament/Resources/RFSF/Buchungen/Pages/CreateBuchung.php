<?php

namespace App\Filament\Resources\RFSF\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\CreateBuchungBase;
use App\Filament\Resources\RFSF\Buchungen\BuchungResource;
use App\Models\RFSF\Buchung;

class CreateBuchung extends CreateBuchungBase
{
    protected static string $resource = BuchungResource::class;

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }
}
