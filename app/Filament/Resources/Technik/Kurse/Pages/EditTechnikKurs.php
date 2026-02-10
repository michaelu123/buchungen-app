<?php

namespace App\Filament\Resources\Technik\Kurse\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Technik\Kurse\TechnikKursResource;

class EditTechnikKurs extends EditRecord
{
    protected static string $resource = TechnikKursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
