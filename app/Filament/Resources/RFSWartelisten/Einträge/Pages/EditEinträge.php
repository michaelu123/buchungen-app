<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge\Pages;

use App\Filament\Resources\RFSWartelisten\Einträge\EinträgeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEinträge extends EditRecord
{
    protected static string $resource = EinträgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
