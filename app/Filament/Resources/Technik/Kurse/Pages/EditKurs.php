<?php

namespace App\Filament\Resources\Technik\Kurse\Pages;

use App\Filament\Resources\KurseBase\EditKurseBase;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\DeleteAction;
use App\Models\Technik\Buchung;
use App\Filament\Resources\Technik\Kurse\KursResource;

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
