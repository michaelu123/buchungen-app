<?php

namespace App\Filament\Resources\RFSFP\Kurse\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Models\RFSFP\Buchung;
use App\Filament\Resources\RFSFP\Kurse\KursResource;

class EditKurs extends EditRecord
{
    protected static string $resource = KursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $buchung = parent::handleRecordUpdate($record, $data);
        Buchung::checkRestplätze();
        return $buchung;
    }

}
