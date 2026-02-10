<?php

namespace App\Filament\Resources\Technik\Kurse\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Technik\Kurse\TechnikKursResource;

class ListTechnikKurse extends ListRecords
{
    protected static string $resource = TechnikKursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
