<?php

namespace App\Models\Technik;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Mail\VerifyEmail;
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
        "eingezogen",
        "betrag",
        "kommentar",
    ];

    public function kurs(): BelongsTo
    {
        return $this->belongsTo(Kurs::class, "kursnummer", "nummer");
    }
    public function checkIban()
    {
        // IBAN is already checked in the frontend, but we want to be sure that no invalid IBAN gets into the database. 
        // So we check it again here and if it's invalid, we send an email to the user and set a note in the database.
        if (!$this->test_iban($this->iban)) {
            $this->update(["notiz" => "Ungültige IBAN"]);
            Mail::to($this->email)->send(new FalscheIban($this->iban));
            Notification::make()
                ->title("Email nicht bestätigt")
                ->warning()
                ->send();
            Notification::make()
                ->title("Ungültige IBAN")
                ->warning()
                ->send();
        }
    }
    public function checkLastschriftOk()
    {
        if (!$this->lastschriftok) {
            $this->update(["notiz" => "Lastschrift nicht erlaubt"]);
            Notification::make()
                ->title("Lastschrift nicht erlaubt")
                ->warning()
                ->send();
        }
    }

    public function checkVerified()
    {
        if (!$this->verified) {
            Mail::to($this->email)->send(new VerifyEmail("xyzabcdef"));
            Notification::make()
                ->title("Email nicht bestätigt")
                ->warning()
                ->send();
        }
    }

    public function check()
    {
        $this->checkIban();
        $this->checkLastschriftOk();
        $this->checkVerified();
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
