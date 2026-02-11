<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Technik\Buchungen\BuchungResource;

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
