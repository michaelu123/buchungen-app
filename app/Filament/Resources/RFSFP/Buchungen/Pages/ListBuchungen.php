<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\RFSFP\Buchungen\BuchungResource;

class ListBuchungen extends ListRecords
{
    protected static string $resource = BuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
