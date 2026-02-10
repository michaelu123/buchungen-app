<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Technik\Buchungen\TechnikBuchungResource;

class ListTechnikBuchungen extends ListRecords
{
    protected static string $resource = TechnikBuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
