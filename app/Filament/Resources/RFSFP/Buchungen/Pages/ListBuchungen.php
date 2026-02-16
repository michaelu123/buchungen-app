<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\ListBuchungenBase;
use App\Filament\Resources\RFSFP\Buchungen\BuchungResource;

class ListBuchungen extends ListBuchungenBase
{
    protected static string $resource = BuchungResource::class;
}
