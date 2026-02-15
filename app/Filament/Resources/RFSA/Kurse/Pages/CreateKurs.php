<?php

namespace App\Filament\Resources\RFSA\Kurse\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\RFSA\Buchung;
use App\Filament\Resources\RFSA\Kurse\KursResource;

class CreateKurs extends CreateRecord
{
    protected static string $resource = KursResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $buchung = parent::handleRecordCreation($data);
        Buchung::checkRestplätze();
        return $buchung;
    }

}
