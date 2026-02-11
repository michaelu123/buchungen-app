<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Technik\Buchungen\BuchungResource;

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
