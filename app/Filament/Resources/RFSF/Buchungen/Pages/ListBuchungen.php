<?php

namespace App\Filament\Resources\RFSF\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\ListBuchungenBase;
use App\Filament\Resources\RFSF\Buchungen\BuchungResource;

class ListBuchungen extends ListBuchungenBase
{
    protected static string $resource = BuchungResource::class;
}
