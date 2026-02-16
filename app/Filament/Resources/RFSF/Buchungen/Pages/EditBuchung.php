<?php

namespace App\Filament\Resources\RFSF\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Models\RFSF\Buchung;
use App\Filament\Resources\RFSF\Buchungen\BuchungResource;

class EditBuchung extends EditRecord
{
    protected static string $resource = BuchungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->after(function (DeleteAction $action) {
                Buchung::checkRestplätze();
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $buchung = parent::handleRecordUpdate($record, $data);
        Buchung::checkRestplätze();
        return $buchung;
    }
}
