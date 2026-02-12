<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use App\Models\EmailVerifikation;
use App\Mail\VerifyEmail;
use App\Mail\FalscheIban;

class BaseBuchung extends Model
{
    public function checkIban()
    {
        // IBAN is already checked in the frontend, but we want to be sure that no invalid IBAN gets into the database. 
        // So we check it again here and if it's invalid, we send an email to the user and set a note in the database.
        if (!$this->test_iban($this->iban)) {
            $this->update(["notiz" => "Ungültige IBAN"]);
            Mail::to($this->email)->send(new FalscheIban($this->iban));
            static::notifyWarning("Ungültige IBAN");
        }
    }

    public function checkLastschriftOk()
    {
        if (!$this->lastschriftok) {
            $this->update(["notiz" => "Lastschrift nicht erlaubt"]);
            static::notifyWarning("Lastschrift nicht erlaubt");
        }
    }

    public function checkVerified()
    {
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
            static::notifyWarning("Email nicht bestätigt");
        }
    }

    protected static function notifyWarning(string $title)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            Notification::make()
                ->title($title)
                ->warning()
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        } else {
            Notification::make()
                ->title($title)
                ->warning()
                ->send();
        }
    }

    protected static function notifySuccess(string $title)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            Notification::make()
                ->title($title)
                ->success()
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        } else {
            Notification::make()
                ->title($title)
                ->success()
                ->send();
        }
    }


    public function check()
    {
        $this->checkIban();
        $this->checkLastschriftOk();
        $this->checkVerified();
        $this->checkRestplätze();
    }

    public static function verifyEmail($email, $now)
    {
        $unverified = static::where('email', $email)->whereNull("verified")->whereNull("notiz")->get();
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
