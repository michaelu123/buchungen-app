<?php

namespace App\Models\Technik;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BaseBuchung;
use App\Mail\Technik\Bestaetigung;

class Buchung extends BaseBuchung
{
    protected bool $confirmAutomatically = false;
    protected $table = "technik_buchungen";

    protected $fillable = [
        "notiz",
        "email",
        "mitgliedsnummer",
        "kursnummer",
        "anrede",
        "vorname",
        "nachname",
        "postleitzahl",
        "ort",
        "strasse_nr",
        "telefonnr",
        "kontoinhaber",
        "iban",
        "lastschriftok",
        "verified",
        "eingezogen",
        "betrag",
        "kommentar",
    ];

    public function kurs(): BelongsTo
    {
        return $this->belongsTo(Kurs::class, "kursnummer", "nummer");
    }

    public static function createBuchung($data): Buchung
    {
        $buchung = self::create($data);

        $kursnummer = $data['kursnummer'];
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
        Buchung::notifySuccess('Buchung erfolgreich angelegt');

        $buchung->check();

        return $buchung;
    }

    public function confirm(): void
    {
        if ($this->notiz || !$this->verified || !$this->lastschriftok) {
            Log::info("2confirm");
            Buchung::notifyWarning("Buchung hat eine Notiz oder ist nicht verifiziert oder Lastschrift verweigert");
            return;
        }
        $kurs = Kurs::where("nummer", $this->kursnummer)->first();
        Mail::to($this->email)->send(new Bestaetigung($kurs, $this));
        Buchung::notifySuccess("Bestätigung versendet");
    }

    public static function checkRestplätze(): void
    {
        Log::info("checkRestplätze");
        $kursBuchungen = Buchung::select("kursnummer", DB::raw("count(*) as count"))
            ->whereNull("notiz")
            ->groupBy("kursnummer")
            ->get()->toArray();
        $kursPlätze = Kurs::select("id", "nummer", "kursplätze", "restplätze")
            ->whereNull("notiz")
            ->get()
            ->toArray();
        foreach ($kursPlätze as $kurs) {
            $buchungenFound = false;
            foreach ($kursBuchungen as $buchung) {
                if ($buchung["kursnummer"] == $kurs["nummer"]) {
                    $buchungenFound = true;
                    $restOld = $kurs["restplätze"];
                    $diff = $kurs["kursplätze"] - $buchung["count"];
                    if ($diff < 0) {
                        $diff = 0;
                        Buchung::notifyWarning("Kurs " . $kurs["nummer"] . " ist überbucht!");
                    }
                    if ($diff != $restOld) {
                        Kurs::find($kurs["id"])->update(["restplätze" => $diff]);
                        Buchung::notifyWarning("Restplätze für Kursnummer " . $kurs["nummer"] . " von " . $restOld . " auf " . $diff . " korrigiert");
                    }
                }
            }
            if (!$buchungenFound) {
                if ($kurs["restplätze"] != $kurs["kursplätze"]) {
                    Kurs::find($kurs["id"])->update(["restplätze" => $kurs["kursplätze"]]);
                    Buchung::notifyWarning("Restplätze für Kursnummer " . $kurs["nummer"] . " von " . $kurs["restplätze"] . " auf " . $kurs["kursplätze"] . " korrigiert");
                }
            }
        }
    }
    public function getFrom(): string
    {
        return "technik_anmeldungen@adfc-muenchen.de";
    }
}
