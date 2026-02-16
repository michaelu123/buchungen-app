<?php

namespace App\Filament\Resources\RFSF\Kurse\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\RFSF\Buchung;
use App\Filament\Resources\RFSF\Kurse\KursResource;

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
