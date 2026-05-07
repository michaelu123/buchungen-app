<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen\Pages;

use App\Filament\Resources\KurseBase\EditKurseBase;
use App\Filament\Resources\Saisonkarten\Buchungen\BuchungResource;
use Filament\Actions\DeleteAction;

class EditBuchung extends EditKurseBase
{
    protected static string $resource = BuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
