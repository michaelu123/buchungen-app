<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Technik\Buchungen\TechnikBuchungResource;

class EditTechnikBuchung extends EditRecord
{
    protected static string $resource = TechnikBuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
