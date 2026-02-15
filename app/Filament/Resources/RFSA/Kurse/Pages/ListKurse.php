<?php

namespace App\Filament\Resources\RFSA\Kurse\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\RFSA\Kurse\KursResource;

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
