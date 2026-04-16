<?php

namespace App\Models\Saisonkarten;

use App\Mail\Saisonkarten\SKMail;
use App\Models\BaseBuchung;
use Illuminate\Database\Eloquent\Builder;
use Imagick;
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

    public $pngPath = '';

    public $jpgPath = '';

    public $pdfPath = '';

    protected $table = 'sk_buchungen';

    public function getFrom(): string
    {
        return 'saisonkarten@adfc-muenchen.de';
    }

    public static function createBuchung(array $data): Buchung
    {

        $buchung = Buchung::whereNull("notiz")
            ->where(function (Builder $query) use ($data) {
                $query->where("mitgliedsname", $data["mitgliedsname"])->orWhere("mitgliedsnummer", $data["mitgliedsnummer"]);
            })->first();
        if ($buchung == null) {
            [$buchung, $basisdaten] = DB::transaction(function () use ($data): array {
                $basisdaten = BasisDaten::first();
                $data['sknummer'] = $basisdaten->sknummer;
                $buchung = Buchung::create($data);
                $basisdaten->sknummer = $basisdaten->sknummer + 1;
                $basisdaten->save();
                return [$buchung, $basisdaten];
            });
        } else {
            $basisdaten = BasisDaten::first();
        }
        static::createSK($buchung, $basisdaten);
        $buchung->check();
        return $buchung;
    }

    private static function createSK(Buchung $buchung, BasisDaten $basisdaten)
    {
        $skPath = Storage::disk('local')->path('SK/' . $basisdaten->jahr);
        $skNummer = $buchung->sknummer;
        $name = $buchung->mitgliedsname;
        $p = str_replace('/', '_', $name);
        $p = str_replace('\\', '_', $p);
        $p = str_replace(' ', '_', $p);
        $skPath = $skPath . '/Saisonkarte_' . $skNummer . '_' . $p;
        $pngPath = $skPath . '.png';
        $jpgPath = $skPath . '.jpg';
        $pdfPath = $skPath . '.pdf';

        if (file_exists($pngPath) && file_exists($jpgPath) && file_exists($pdfPath)) {
            $buchung->pngPath = $pngPath;
            $buchung->jpgPath = $jpgPath;
            $buchung->pdfPath = $pdfPath;
            return;
        }

        $img = imagecreatefrompng(app_path('Models/Saisonkarten/Saisonkarte-blank.png'));
        $oswr = app_path('Models/Saisonkarten/Oswald-Regular.ttf');
        $oswl = app_path('Models/Saisonkarten/Oswald-Light.ttf');
        if (!is_dir($skPath)) {
            mkdir($skPath, 0755, true);
        }

        $year = $basisdaten->jahr;
        $nummer = $buchung->mitgliedsnummer;
        $gültigAb = $basisdaten->gueltigab;
        $gültigBis = $basisdaten->gueltigbis;
        $betrag = $basisdaten->betrag;

        imagefttext($img, 50, 0, 800, 100, 0, $oswr, $year);
        imagefttext($img, 50, 0, 640, 180, 0, $oswr, 'Saisonkarte');
        imagefttext($img, 50, 0, 800, 260, 0, $oswr, sprintf('#%3d', $skNummer));
        imagefttext($img, 30, 0, 40, 350, 0, $oswr, sprintf('Name: %s', $name));
        imagefttext($img, 30, 0, 40, 420, 0, $oswr, sprintf('Mitgliedsnummer: %d', $nummer));
        imagefttext($img, 30, 0, 40, 490, 0, $oswr, sprintf('Gültig ab: %s', $gültigAb));
        imagefttext($img, 30, 0, 40, 560, 0, $oswr, sprintf('Gültig bis: %s', $gültigBis));
        imagefttext($img, 30, 0, 800, 560, 0, $oswr, sprintf('%d€', $betrag));
        imagefttext(
            $img,
            14,
            0,
            20,
            630,
            0,
            $oswl,
            'Für alle Tagestouren des ADFC München. Zusatzkosten wie Tickets, Mieten, Eintritte sind NICHT Bestandteil des Saisontickets.
Bei Beginn der Tour muss diese Karte (ausgedruckt oder auf dem Smartphone) mit dem Mitgliedsausweis vorgezeigt werden. 
Ohne Mitgliedsausweis ist die Saisonkarte nicht gültig.'
        );

        imagepng($img, $pngPath);
        $buchung->pngPath = $pngPath;
        imagejpeg($img, $jpgPath);
        $buchung->jpgPath = $jpgPath;

        /** @var Imagick $img */
        $img = new Imagick($jpgPath);
        $img->setImageFormat('pdf');
        $img->writeImage($pdfPath);
        $buchung->pdfPath = $pdfPath;
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
        if (!str_ends_with($this->email, '@adfc-muenchen.de')) {
            return;
        } // TODO

        $basisdaten = BasisDaten::first();
        try {
            Mail::to($this->email)->send(new SKMail($this, $basisdaten));
            $this->update(['gesendet' => now()]);
        } catch (Throwable $t) {
            Log::error('error ' . $t->getMessage());
            Log::error('error ' . $t);
        }
        static::notifySuccess('Bestätigung versendet');
    }
}
