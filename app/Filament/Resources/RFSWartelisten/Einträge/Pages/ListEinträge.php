<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge\Pages;

use App\Filament\Resources\RFSWartelisten\Einträge\EinträgeResource;
use Filament\Resources\Pages\ListRecords;

class ListEinträge extends ListRecords
{
    protected static string $resource = EinträgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
