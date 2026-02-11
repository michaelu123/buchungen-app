<?php

namespace App\Models\Technik;

use Mockery\Matcher\Not;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use App\Models\EmailVerifikation;
use App\Mail\VerifyEmail;
use App\Mail\Technik\Bestätigung;
use App\Mail\FalscheIban;

class Buchung extends Model
{
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

    public function checkIban()
    {
        // IBAN is already checked in the frontend, but we want to be sure that no invalid IBAN gets into the database. 
        // So we check it again here and if it's invalid, we send an email to the user and set a note in the database.
        if (!$this->test_iban($this->iban)) {
            $this->update(["notiz" => "Ungültige IBAN"]);
            Mail::to($this->email)->send(new FalscheIban($this->iban));
            Buchung::notifyWarning("Ungültige IBAN");
        }
    }

    public function confirm()
    {
        if ($this->notiz || !$this->verified || !$this->lastschriftok) {
            Log::info("2confirm");
            Buchung::notifyWarning("Buchung hat eine Notiz oder ist nicht verifiziert oder Lastschrift verweigert");
            return;
        }
        $kurs = Kurs::where("nummer", $this->kursnummer)->first();
        Mail::to($this->email)->send(new Bestätigung($kurs, $this));
        Buchung::notifySuccess("Bestätigung versendet");
    }
    public function checkLastschriftOk()
    {
        Log::info("checkLastschriftOk: " . $this->lastschriftok);
        if (!$this->lastschriftok) {
            $this->update(["notiz" => "Lastschrift nicht erlaubt"]);
            Buchung::notifyWarning("Lastschrift nicht erlaubt");
        }
    }

    public function checkVerified()
    {
        Log::info("checkVerified: " . $this->verified);
        if (!$this->verified) {
            $ev = EmailVerifikation::where("email", $this->email)->first();
            if ($ev && $ev->verified) {
                $this->update(["verified" => $ev->verified]);
                if (!$this->notiz) {
                    $this->confirm();
                }
            }
        }
        if (!$this->verified) {
            Mail::to($this->email)->send(new VerifyEmail($this->email));
            Buchung::notifyWarning("Email nicht bestätigt");
        }
    }

    protected static function notifyWarning(string $title)
    {
        Log::info("1notifyWarning");
        $user = Auth::user();
        if ($user) {
            Log::info("2notifyWarning");
            Notification::make()
                ->title($title)
                ->warning()
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        } else {
            Log::info("3notifyWarning");
            Notification::make()
                ->title($title)
                ->warning()
                ->send();
        }
    }

    protected static function notifySuccess(string $title)
    {
        Log::info("1notifySuccess");
        $user = Auth::user();
        if ($user) {
            Log::info("2notifySuccess");
            Notification::make()
                ->title($title)
                ->success()
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        } else {
            Log::info("3notifySuccess");
            Notification::make()
                ->title($title)
                ->success()
                ->send();
        }
    }

    public static function checkRestplätze()
    {
        Log::info("checkRestplätze");
        $kursBuchungen = Buchung::select("kursnummer", DB::raw("count(*) as count"))
            ->whereNull("notiz")
            ->groupBy("kursnummer")
            ->get()->toArray();
        $kursPlätze = Kurs::select("id", "nummer", "kursplätze", "restplätze")->get()->toArray();
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

    public function check()
    {
        Log::info("check");
        $this->checkIban();
        $this->checkLastschriftOk();
        $this->checkVerified();
        $this->checkRestplätze();
    }

    public static function verifyEmail($email, $now)
    {
        Log::info("verifyEmail");
        $unverified = Buchung::where('email', $email)->whereNull("verified")->whereNull("notiz")->get();
        $unverified->each(function ($buchung) use ($now) {
            $buchung->update(['verified' => $now]);
            $buchung->confirm();
        });
    }

    # https://gist.github.com/ahoehne/926b50a8a548801c5b52
    ########################################################
    # Funktion zur Plausibilitaetspruefung einer IBAN-Nummer, gilt fuer alle Laender
    # Das Ganze ist deswegen etwas spannend, weil eine Modulo-Rechnung, also eine Ganzzahl-Division mit einer 
    # bis zu 38-stelligen Ganzzahl durchgefuehrt werden muss. Wegen der meist nur zur Verfuegung stehenden 
    # 32-Bit-CPUs koennen mit PHP aber nur maximal 9 Stellen mit allen Ziffern genutzt werden. 
    # Deshalb muss die Modulo-Rechnung in mehere Teilschritte zerlegt werden.
    # http://www.michael-schummel.de/2007/10/05/iban-prufung-mit-php
    ########################################################
    public static function test_iban($iban)
    {
        $iban = str_replace(' ', '', $iban);
        $iban1 = substr($iban, 4)
            . \strval(\ord($iban[0]) - 55)
            . \strval(\ord($iban[1]) - 55)
            . substr($iban, 2, 2);

        for ($i = 0; $i < strlen($iban1); $i++) {
            if (\ord($iban1[$i]) > 64 && \ord($iban1[$i]) < 91) {
                $iban1 = substr($iban1, 0, $i) . \strval(\ord($iban1[$i]) - 55) . substr($iban1, $i + 1);
            }
        }
        $rest = 0;
        for ($pos = 0; $pos < strlen($iban1); $pos += 7) {
            $part = \strval($rest) . substr($iban1, $pos, 7);
            $rest = \intval($part) % 97;
        }

        // $pz = \sprintf("%02d", 98 - $rest);

        // ??
        // if (substr($iban, 2, 2) == '00')
        //     return substr_replace($iban, $pz, 2, 2);
        // else
        return $rest == 1;
    }
}
