<?php

namespace App\Filament\Resources\Technik\Buchungen\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;
use App\Filament\Resources\Technik\Buchungen\BuchungResource;

class CreateBuchung extends CreateRecord
{
    protected static string $resource = BuchungResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $kursnummer = $data['kursnummer'];
        $res = null;
        $buchungenCount = Buchung::where('kursnummer', $kursnummer)
            ->whereNull("notiz")->count();
        $kurs = Kurs::where('nummer', $kursnummer)->first();
        if ($kurs && $kurs->restplätze > 0) {
            $kurs->restplätze = $kurs->kursplätze - $buchungenCount - 1;
        }
        if ($kurs->restplätze < 0) {
            // Handle the case where there are no available spots
            throw new \Exception('Keine verfügbaren Plätze für diesen Kurs.');
        }
        $kurs->save();
        $buchung = Buchung::create($data);
        $this->check($buchung);
        return $buchung;
    }

    protected function check(Buchung $buchung): void
    {
        $buchung->checkIban();
        $buchung->checkLastschriftOk();
        $buchung->checkVerified();
    }
}
