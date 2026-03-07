<?php

namespace App\Filament\Resources\Codier\Termine\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use App\Models\Codier\Buchung;
use App\Filament\Resources\Codier\Termine\TerminResource;

class EditTermin extends EditRecord
{
    protected static string $resource = TerminResource::class;

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
