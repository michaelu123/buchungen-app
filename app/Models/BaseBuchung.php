<?php

namespace App\Models;

use App\Mail\FalscheIban;
use App\Mail\VerifyEmail;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BaseBuchung extends Model
{
    protected $fillable = [
        'created_at',
        'notiz',
        'email',
        'mitgliedsnummer',
        'kursnummer',
        'anrede',
        'vorname',
        'nachname',
        'postleitzahl',
        'ort',
        'strasse_nr',
        'telefonnr',
        'kontoinhaber',
        'iban',
        'lastschriftok',
        'verified',
        'eingezogen',
        'betrag',
        'kommentar',
    ];

    protected bool $confirmAutomatically; // redefined in subclass

    public static function kursClass(): string
    {
        $ns = (new \ReflectionClass(static::class))->getNamespaceName();
        $parts = explode('\\', $ns);
        $segment = end($parts);
        $kursClass = "App\\Models\\{$segment}\\Kurs";
        return $kursClass;
    }

    public function kurs(): BelongsTo
    {
        $kursClass = self::kursClass();
        return $this->belongsTo($kursClass, 'kursnummer', 'nummer');
    }

    public static function createBuchung($data): BaseBuchung
    {
        $buchungClass = static::class;
        $buchung = $buchungClass::create($data);

        $kursClass = self::kursClass();
        $kursnummer = $data['kursnummer'];
        $buchungenCount = $buchungClass::where('kursnummer', $kursnummer)
            ->whereNull('notiz')->count();
        $kurs = $kursClass::where('nummer', $kursnummer)->first();
        if ($kurs && $kurs->restplätze > 0) {
            $kurs->restplätze = $kurs->kursplätze - $buchungenCount - 1;
        }
        if ($kurs->restplätze < 0) {
            throw new \Exception('Keine verfügbaren Plätze für diesen Kurs.');
        }
        $kurs->save();
        $buchungClass::notifySuccess('Buchung erfolgreich angelegt');
        $buchung->check();
        return $buchung;
    }

    public function confirm(): void
    {
        if ($this->notiz || !$this->verified || !$this->lastschriftok) {
            Log::info('2confirm');
            static::notifyWarning('Buchung hat eine Notiz oder ist nicht verifiziert oder Lastschrift verweigert');
            return;
        }

        $ns = (new \ReflectionClass(static::class))->getNamespaceName();
        $parts = explode('\\', $ns);
        $segment = end($parts);
        $kursClass = "App\\Models\\{$segment}\\Kurs";
        $mailClass = "App\\Mail\\{$segment}\\Bestaetigung";

        $kurs = $kursClass::where('nummer', $this->kursnummer)->first();
        if (class_exists($mailClass)) {
            Mail::to($this->email)->send(new $mailClass($kurs, $this));
            static::notifySuccess('Bestätigung versendet');
        } else {
            Log::warning('Mailable not found: ' . $mailClass);
        }
    }

    public static function checkRestplätze(): void
    {
        Log::info('checkRestplätze');
        Log::info('selfclass' . self::class);
        Log::info('staticclass' . static::class);
        Log::info('$ns ' . (new \ReflectionClass(static::class))->getNamespaceName());


        $buchungClass = static::class;

        $kursClass = self::kursClass();
        $kursBuchungen = $buchungClass::select('kursnummer', DB::raw('count(*) as count'))
            ->whereNull('notiz')
            ->groupBy('kursnummer')
            ->get()->toArray();
        $kursPlätze = $kursClass::select('id', 'nummer', 'kursplätze', 'restplätze')
            ->whereNull('notiz')
            ->get()
            ->toArray();
        foreach ($kursPlätze as $kurs) {
            $buchungenFound = false;
            foreach ($kursBuchungen as $buchung) {
                if ($buchung['kursnummer'] == $kurs['nummer']) {
                    $buchungenFound = true;
                    $restOld = $kurs['restplätze'];
                    $diff = $kurs['kursplätze'] - $buchung['count'];
                    if ($diff < 0) {
                        $diff = 0;
                        $buchungClass::notifyWarning('Kurs ' . $kurs['nummer'] . ' ist überbucht!');
                    }
                    if ($diff != $restOld) {
                        $kursClass::find($kurs['id'])->update(['restplätze' => $diff]);
                        $buchungClass::notifyWarning('Restplätze für Kursnummer ' . $kurs['nummer'] . ' von ' . $restOld . ' auf ' . $diff . ' korrigiert');
                    }
                }
            }
            if (!$buchungenFound && $kurs['restplätze'] != $kurs['kursplätze']) {
                $kursClass::find($kurs['id'])->update(['restplätze' => $kurs['kursplätze']]);
                $buchungClass::notifyWarning('Restplätze für Kursnummer ' . $kurs['nummer'] . ' von ' . $kurs['restplätze'] . ' auf ' . $kurs['kursplätze'] . ' korrigiert');
            }
        }
    }

    public function checkIban(): void
    {
        // IBAN is already checked in the frontend, but we want to be sure that no invalid IBAN gets into the database.
        // So we check it again here and if it's invalid, we send an email to the user and set a note in the database.
        if (!static::test_iban($this->iban)) {
            $this->update(['notiz' => 'Ungültige IBAN']);
            Mail::to($this->email)->send(new FalscheIban($this->iban, $this->getFrom()));
            static::notifyWarning('Ungültige IBAN');
        }
    }

    public function checkLastschriftOk(): void
    {
        if (!$this->lastschriftok) {
            $this->update(['notiz' => 'Lastschrift nicht erlaubt']);
            static::notifyWarning('Lastschrift nicht erlaubt');
        }
    }

    public function checkVerified(): void
    {
        if (!$this->verified) {
            $ev = EmailVerifikation::where('email', $this->email)->first();
            if ($ev && $ev->verified) {
                $this->update(['verified' => $ev->verified]);
                if (!$this->notiz && $this->confirmAutomatically) {
                    $this->confirm();
                }
            }
        }
        if (!$this->verified) {
            Mail::to($this->email)->send(new VerifyEmail($this->email, $this->getFrom()));
            static::notifyWarning('Email nicht bestätigt');
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

    public function check(): void
    {
        $this->checkIban();
        $this->checkLastschriftOk();
        $this->checkVerified();
        static::checkRestplätze();
    }

    public static function verifyEmail($email, $now): void
    {
        $unverified = static::where('email', $email)->whereNull('verified')->whereNull('notiz')->get();
        $unverified->each(function ($buchung) use ($now): void {
            $buchung->update(['verified' => $now]);
            if (!$buchung->notiz && $buchung->confirmAutomatically) {
                $buchung->confirm();
            }
        });
    }
    // https://gist.github.com/ahoehne/926b50a8a548801c5b52

    // #######################################################
    // Funktion zur Plausibilitaetspruefung einer IBAN-Nummer, gilt fuer alle Laender
    // Das Ganze ist deswegen spannend, weil eine Modulo-Rechnung, also eine Ganzzahl-Division mit einer
    // bis zu 38-stelligen Ganzzahl durchgefuehrt werden muss.
    // Mit 32-Bit-CPUs kann PHP nur mit maximal 9 Stellen mit allen Ziffern genutzt werden.
    // Deshalb musste die Modulo-Rechnung in mehere Teilschritte zerlegt werden.
    // Dies wäre mit bcmod() einfacher, würde jedoch die installation von php-bcmath voraussetzen.
    // #######################################################

    protected static function test_iban(string $iban): bool
    {
        $normalizedIban = strtoupper(str_replace(' ', '', $iban));

        if ($normalizedIban === '') {
            return false;
        }

        if (!preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]+$/', $normalizedIban)) {
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
            'XK' => 20,
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
