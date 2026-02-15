<?php

namespace App\Filament\Resources\RFSA\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\RFSA\Buchung;
use App\Filament\Resources\RFSA\Buchungen\BuchungResource;

class CreateBuchung extends CreateRecord
{
    protected static string $resource = BuchungResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $buchung = parent::handleRecordCreation($data);
        Buchung::checkRestplätze();
        return $buchung;
    }
}
