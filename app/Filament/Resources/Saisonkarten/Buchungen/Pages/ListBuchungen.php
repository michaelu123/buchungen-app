<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen\Pages;

use App\Filament\Resources\Saisonkarten\Buchungen\BuchungResource;
use Filament\Resources\Pages\ListRecords;

class ListBuchungen extends ListRecords
{
    protected static string $resource = BuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
