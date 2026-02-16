<?php

namespace App\Filament\Resources\RFSA\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\ListBuchungenBase;
use App\Filament\Resources\RFSA\Buchungen\BuchungResource;

class ListBuchungen extends ListBuchungenBase
{
    protected static string $resource = BuchungResource::class;
}
