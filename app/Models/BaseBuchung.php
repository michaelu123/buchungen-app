<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use App\Models\EmailVerifikation;
use App\Mail\VerifyEmail;
use App\Mail\FalscheIban;

class BaseBuchung extends Model
{
    protected bool $confirmAutomatically;

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
                if (!$this->notiz && $this->confirmAutomatically) {
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
            if (!$this->notiz && $this->confirmAutomatically) {
                $buchung->confirm();
            }
        });
    }

    # https://gist.github.com/ahoehne/926b50a8a548801c5b52

    ########################################################
    # Funktion zur Plausibilitaetspruefung einer IBAN-Nummer, gilt fuer alle Laender
    # Das Ganze ist deswegen spannend, weil eine Modulo-Rechnung, also eine Ganzzahl-Division mit einer
    # bis zu 38-stelligen Ganzzahl durchgefuehrt werden muss.
    # Mit 32-Bit-CPUs kann PHP nur mit maximal 9 Stellen mit allen Ziffern genutzt werden.
    # Deshalb musste die Modulo-Rechnung in mehere Teilschritte zerlegt werden.
    # Dies wäre mit bcmod() einfacher, würde jedoch die installation von php-bcmath voraussetzen.
    ########################################################

    function test_iban(string $iban): bool
    {
        $normalizedIban = strtoupper(str_replace(' ', '', $iban));

        if ($normalizedIban === '') {
            return false;
        }

        if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/', $normalizedIban)) {
            return false;
        }

        // IBAN registry provides current data for this
        // https://www.swift.com/standards/data-standards/iban-international-bank-account-number
        $expectedLengthsByCountry = [
            'AD' => 24,
            'AE' => 23,
            'AL' => 28,
            'AT' => 20,
            'AZ' => 28,
            'BA' => 20,
            'BE' => 16,
            'BG' => 22,
            'BH' => 22,
            'BI' => 27,
            'BR' => 29,
            'BY' => 28,
            'CH' => 21,
            'CR' => 22,
            'CY' => 28,
            'CZ' => 24,
            'DE' => 22,
            'DJ' => 27,
            'DK' => 18,
            'DO' => 28,
            'EE' => 20,
            'EG' => 29,
            'ES' => 24,
            'FI' => 18,
            'FK' => 18,
            'FO' => 18,
            'FR' => 27,
            'GB' => 22,
            'GE' => 22,
            'GI' => 23,
            'GL' => 18,
            'GR' => 27,
            'GT' => 28,
            'HR' => 21,
            'HU' => 28,
            'IE' => 22,
            'IL' => 23,
            'IQ' => 23,
            'IS' => 26,
            'IT' => 27,
            'JO' => 30,
            'KZ' => 20,
            'KW' => 30,
            'LB' => 28,
            'LC' => 32,
            'LI' => 21,
            'LT' => 20,
            'LU' => 20,
            'LV' => 21,
            'LY' => 25,
            'MC' => 27,
            'MD' => 24,
            'ME' => 22,
            'MK' => 19,
            'MN' => 20,
            'MR' => 27,
            'MT' => 31,
            'MU' => 30,
            'NI' => 28,
            'NL' => 18,
            'NO' => 15,
            'OM' => 23,
            'PK' => 24,
            'PL' => 28,
            'PS' => 29,
            'PT' => 25,
            'QA' => 29,
            'RO' => 24,
            'RS' => 22,
            'RU' => 33,
            'SA' => 24,
            'SC' => 31,
            'SD' => 18,
            'SE' => 24,
            'SI' => 19,
            'SK' => 24,
            'SM' => 27,
            'SO' => 23,
            'ST' => 25,
            'SV' => 28,
            'TL' => 23,
            'TN' => 24,
            'TR' => 26,
            'UA' => 29,
            'VA' => 22,
            'VG' => 24,
            'XK' => 20
        ];

        $countryCode = substr($normalizedIban, 0, 2);
        if (
            !isset($expectedLengthsByCountry[$countryCode]) ||
            \strlen($normalizedIban) !== $expectedLengthsByCountry[$countryCode]
        ) {
            return false;
        }

        $rearrangedIban = substr($normalizedIban, 4) . substr($normalizedIban, 0, 4);
        $letterToDigitMap = array_combine(range('A', 'Z'), range(10, 35));
        $numericIban = strtr($rearrangedIban, $letterToDigitMap);

        $remainder = 0;
        for ($i = 0, $length = \strlen($numericIban); $i < $length; $i++) {
            $remainder = (int) ($remainder . $numericIban[$i]) % 97;
        }
        return $remainder === 1;
    }
}