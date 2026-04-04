<?php

namespace App\Models\Codier;

use App\Mail\Codier\Bestaetigung;
use App\Models\BaseBuchung;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Buchung extends BaseBuchung
{
    use HasFactory;

    protected $fillable = [
        'termin_id',
        'uhrzeit',
        'anrede',
        'vorname',
        'nachname',
        'postleitzahl',
        'ort',
        'strasse',
        'hsnr',
        'ein',
        'telefonnr',
        'email',
        'mitgliedsnummer',
        'notiz',
        'anmeldebestätigung',
        'kommentar',
        'created_at',
    ];


    public static bool $confirmAutomatically = true;
    public static bool $requireEmailVerification = false;
    public static bool $requireAbbuchung = false;

    protected static ?string $kursClass = Termin::class;
    protected static ?string $bestätigungClass = Bestaetigung::class;


    protected $table = "codier_buchungen";
    public function getFrom(): string
    {
        return "anmeldungen-codierung@adfc-muenchen.de";
    }

    public static function checkRestplätze(): void
    {
    }

    public static function createBuchung($data): Buchung
    {
        function decode(string $s)
        {
            return utf8_decode($s);
        }

        $data["kursnummer"] = $data["termin_id"];
        try {
            $einResp = Http::get("https://fa-technik-adfc.de/ein", [
                "name" => decode($data["ort"]),
                "str" => decode($data["strasse"]),
                "hsnr" => $data["hsnr"] ?? "1",
                "n1" => decode($data["vorname"]),
                "n2" => decode($data["nachname"]),
            ]);
            $status = $einResp->status();
            $ein = $einResp->body();
            // Log::info("status " . $status . ", body " . $ein);
            $pos = strpos($ein, "<big><big>");
            $ein = substr($ein, $pos + 25, 16);
            $data["ein"] = $ein;
        } catch (Exception $e) {
            Log::error("cannot get EIN: " . $e);
            $data["ein"] = "";

        }
        $buchung = Buchung::create($data);
        Buchung::notifySuccess('Termin erfolgreich gebucht');
        $buchung->check();
        return $buchung;
    }

    public function confirm(): void
    {
        if ($this->notiz) {
            static::notifyWarning('Buchung hat eine Notiz');
            return;
        }
        if (!str_ends_with($this->email, "@adfc-muenchen.de")) {
            return;
        } // TODO 
        $termin = Termin::find($this->termin_id);
        try {
            Mail::to($this->email)->send(new Bestaetigung($termin, $this));
            $this->update(['anmeldebestätigung' => now()]);
        } catch (Throwable $t) {
            Log::error("error " . $t->getMessage());
            Log::error("error " . $t);
        }
        static::notifySuccess('Bestätigung versendet');
    }

    public function termin(): BelongsTo
    {
        return $this->belongsTo(static::$kursClass, 'termin_id', 'id');
    }
}
