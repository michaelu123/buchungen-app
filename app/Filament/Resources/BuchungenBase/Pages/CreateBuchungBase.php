<?php

namespace App\Filament\Resources\BuchungenBase\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

abstract class CreateBuchungBase extends CreateRecord
{
    abstract protected static function getBuchungModelClass(): string;

    protected function handleRecordCreation(array $data): Model
    {
        $buchung = parent::handleRecordCreation($data);
        $buchungClass = static::getBuchungModelClass();
        $buchungClass::checkRestplätze();

        return $buchung;
    }
}
