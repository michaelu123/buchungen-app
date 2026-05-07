<?php

namespace App\Filament\Resources\RFSA\Kurse\Pages;

use App\Filament\Resources\KurseBase\EditKurseBase;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\DeleteAction;
use App\Models\RFSA\Buchung;
use App\Filament\Resources\RFSA\Kurse\KursResource;

class EditKurs extends EditKurseBase
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
