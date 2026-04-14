<?php

namespace App\Models\Saisonkarten;

use App\Mail\Saisonkarten\SKMail;
use App\Models\BaseBuchung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Buchung extends BaseBuchung
{
    protected $fillable = [
        'notiz',
        'email',
        'mitgliedsnummer',
        'mitgliedsname',
        'nochmal',
        'sknummer',
        'kontoinhaber',
        'iban',
        'lastschriftok',
        'verified',
        'gesendet',
        'eingezogen',
        'betrag',
        'kommentar',
    ];

    public static bool $confirmAutomatically = true; // redefined in subclass
    public static bool $requireEmailVerification = true; // redefined in subclass
    public static bool $requireAbbuchung = true; // redefined in subclass

    protected $table = "sk_buchungen";
    public function getFrom(): string
    {
        return "saisonkarten@adfc-muenchen.de";
    }

    public static function createBuchung(array $data): Buchung
    {
        $buchung = DB::transaction(function () use ($data): Buchung {
            $basisdaten = BasisDaten::first();
            $data["sknummer"] = $basisdaten->sknummer;
            $buchung = Buchung::create($data);
            $basisdaten->sknummer = $basisdaten->sknummer + 1;
            $basisdaten->save();
            return $buchung;
        });
        $buchung->check();
        return $buchung;
    }

    public static function checkRestplätze(): void
    {
    }

    public function confirm(): void
    {
        if ($this->notiz) {
            static::notifyWarning('Buchung hat eine Notiz');
            return;
        }
        if (static::$requireEmailVerification && !$this->verified) {
            static::notifyWarning('Buchung ist nicht verifiziert');
            return;
        }
        if (static::$requireAbbuchung && !$this->lastschriftok) {
            static::notifyWarning('Buchung hat Lastschrift verweigert');
            return;
        }


        if (!str_ends_with($this->email, "@adfc-muenchen.de")) {
            return;
        } // TODO 
        try {
            Mail::to($this->email)->send(new SKMail($this, ));
            $this->update(['gesendet' => now()]);
        } catch (Throwable $t) {
            Log::error("error " . $t->getMessage());
            Log::error("error " . $t);
        }
        static::notifySuccess('Bestätigung versendet');
    }


}
