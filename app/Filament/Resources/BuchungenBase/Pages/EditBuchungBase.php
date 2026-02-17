<?php

namespace App\Filament\Resources\BuchungenBase\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

abstract class EditBuchungBase extends EditRecord
{
    abstract protected static function getBuchungModelClass(): string;

    protected function getHeaderActions(): array
    {
        $buchungClass = static::getBuchungModelClass();

        return [
            DeleteAction::make()->after(function (DeleteAction $action) use ($buchungClass): void {
                $buchungClass::checkRestplätze();
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $buchung = parent::handleRecordUpdate($record, $data);
        $buchungClass = static::getBuchungModelClass();
        $buchungClass::checkRestplätze();

        return $buchung;
    }
}
