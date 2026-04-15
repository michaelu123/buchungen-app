<?php

namespace App\Models\Saisonkarten;

use App\Mail\Saisonkarten\SKMail;
use App\Models\BaseBuchung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public $skPath = "";

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
            static::createSK($buchung, $basisdaten);
            return $buchung;
        });
        $buchung->check();
        return $buchung;
    }

    private static function createSK(Buchung $buchung, BasisDaten $basisdaten)
    {
        $img = imagecreatefrompng(app_path('Models/Saisonkarten/Saisonkarte-blank.png'));
        $oswr = app_path('Models/Saisonkarten/Oswald-Regular.ttf');
        $oswl = app_path('Models/Saisonkarten/Oswald-Light.ttf');
        $skPath = Storage::disk('local')->path('SK/' . $basisdaten->jahr);
        if (!is_dir($skPath)) {
            mkdir($skPath, 0755, true);
        }

        $year = $basisdaten->jahr;
        $name = $buchung->mitgliedsname;
        $nummer = $buchung->mitgliedsnummer;
        $skNummer = $buchung->sknummer;
        $gültigAb = $basisdaten->gueltigab;
        $gültigBis = $basisdaten->gueltigbis;
        $betrag = $basisdaten->betrag;

        $p = str_replace('/', '_', $name);
        $p = str_replace('\\', '_', $p);
        $skPath = $skPath . '/Saisonkarte_' . $skNummer . '_' . $p . '.png';

        imagefttext($img, 50, 0, 800, 100, 0, $oswr, $year);
        imagefttext($img, 50, 0, 640, 180, 0, $oswr, "Saisonkarte");
        imagefttext($img, 50, 0, 800, 260, 0, $oswr, sprintf("#%3d", $skNummer));
        imagefttext($img, 30, 0, 40, 350, 0, $oswr, sprintf("Name: %s", $name));
        imagefttext($img, 30, 0, 40, 420, 0, $oswr, sprintf("Mitgliedsnummer: %d", $nummer));
        imagefttext($img, 30, 0, 40, 490, 0, $oswr, sprintf("Gültig ab: %s", $gültigAb));
        imagefttext($img, 30, 0, 40, 560, 0, $oswr, sprintf("Gültig bis: %s", $gültigBis));
        imagefttext($img, 30, 0, 800, 560, 0, $oswr, sprintf("%d€", $betrag));
        imagefttext(
            $img,
            14,
            0,
            20,
            630,
            0,
            $oswl,
            "Für alle Tagestouren des ADFC München. Zusatzkosten wie Tickets, Mieten, Eintritte sind NICHT Bestandteil des Saisontickets.
Bei Beginn der Tour muss diese Karte (ausgedruckt oder auf dem Smartphone) mit dem Mitgliedsausweis vorgezeigt werden. 
Ohne Mitgliedsausweis ist die Saisonkarte nicht gültig."
        );

        // header("Content-type: image/png");
        imagepng($img, $skPath);
        $buchung->skPath = $skPath;
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
            Mail::to($this->email)->send(new SKMail($this));
            $this->update(['gesendet' => now()]);
        } catch (Throwable $t) {
            Log::error("error " . $t->getMessage());
            Log::error("error " . $t);
        }
        static::notifySuccess('Bestätigung versendet');
    }


}
