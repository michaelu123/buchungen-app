<?php

namespace App\Filament\Resources\Codier\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\ListBuchungenBase;
use App\Filament\Resources\Codier\Buchungen\BuchungResource;

class ListBuchungen extends ListBuchungenBase
{
    protected static string $resource = BuchungResource::class;
}
