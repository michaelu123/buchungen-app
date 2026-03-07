<?php

namespace App\Filament\Resources\Codier\Termine\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Codier\Buchung;
use App\Filament\Resources\Codier\Termine\TerminResource;

class CreateTermin extends CreateRecord
{
    protected static string $resource = TerminResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $buchung = parent::handleRecordCreation($data);
        Buchung::checkRestplätze();
        return $buchung;
    }

}
