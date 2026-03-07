<?php

namespace App\Filament\Resources\Codier\Termine\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Codier\Termine\TerminResource;

class ListTermine extends ListRecords
{
    protected static string $resource = TerminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
