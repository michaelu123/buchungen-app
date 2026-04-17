<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen\Pages;

use App\Filament\Resources\Saisonkarten\Buchungen\BuchungResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuchung extends EditRecord
{
    protected static string $resource = BuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
