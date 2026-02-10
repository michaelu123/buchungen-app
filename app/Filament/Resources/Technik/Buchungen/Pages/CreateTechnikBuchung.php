<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\TechnikKurs;
use App\Models\TechnikBuchung;
use App\Filament\Resources\Technik\Buchungen\TechnikBuchungResource;

class CreateTechnikBuchung extends CreateRecord
{
    protected static string $resource = TechnikBuchungResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $kursnummer = $data['kursnummer'];
        $res = null;
        $buchungenCount = TechnikBuchung::where('kursnummer', $kursnummer)
            ->whereNull("notiz")->count();
        $kurs = TechnikKurs::where('nummer', $kursnummer)->first();
        if ($kurs && $kurs->restplätze > 0) {
            $kurs->restplätze = $kurs->kursplätze - $buchungenCount - 1;
        }
        if ($kurs->restplätze < 0) {
            // Handle the case where there are no available spots
            throw new \Exception('Keine verfügbaren Plätze für diesen Kurs.');
        }
        $kurs->save();
        $buchung = TechnikBuchung::create($data);
        $this->check($buchung);
        return $buchung;
    }

    protected function check(TechnikBuchung $buchung): void
    {
        $buchung->checkIban();
        $buchung->checkLastschriftOk();
        $buchung->checkVerified();
    }
}
