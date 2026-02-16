<?php

namespace App\Filament\Resources\RFSF\Kurse\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\RFSF\Kurse\KursResource;

class ListKurse extends ListRecords
{
    protected static string $resource = KursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
