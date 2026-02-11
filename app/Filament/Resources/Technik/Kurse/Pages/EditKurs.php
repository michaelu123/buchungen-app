<?php

namespace App\Filament\Resources\Technik\Kurse\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Technik\Kurse\KursResource;

class EditKurs extends EditRecord
{
    protected static string $resource = KursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
