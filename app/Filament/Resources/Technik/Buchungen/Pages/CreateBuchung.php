<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Technik\Buchung;
use App\Filament\Resources\Technik\Buchungen\BuchungResource;

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
