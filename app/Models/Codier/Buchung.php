<?php

namespace App\Models\Codier;

use App\Mail\Codier\Bestaetigung;
use App\Models\BaseBuchung;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

    protected $table = 'codier_buchungen';

    public function getFrom(): string
    {
        return 'anmeldungen-codierung@adfc-muenchen.de';
    }

    public static function checkRestplätze(): void
    {
    }

    private static function decode(string $s): string
    {
        return utf8_decode(trim($s));
    }

    private static function encode(string $s): string
    {
        return utf8_encode(trim($s));
    }

    public static function getEIN(array &$data): void
    {
        $buchung = new static($data);
        $result = $buchung->fetchEIN();
        if ($result['status'] === 'success') {
            $data['ein'] = $result['ein'];
        } elseif ($result['status'] === 'ambiguous') {
            $data['ein'] = '?';
        } else {
            $data['ein'] = $result['message'] ?? 'Fehler';
        }
    }

    public function fetchEIN(?string $url = null): array
    {
        // if (!str_contains($url, 'ags=')) {
        //     return [
        //         "status" => "ambiguous",
        //         "options" => [
        //             b"https://fa-technik-adfc.de/ein?ags=09184118;;str=Beethovenstraße;hsnr=9;n1=Anja;n2=Lekies" => "M 118: Feldkirchen, Kreis München (PLZ: 85622)",
        //             b"https://fa-technik-adfc.de/ein?ags=09187130;;str=Beethovenstraße;hsnr=9;n1=Anja;n2=Lekies" => "RO 130: Feldkirchen-Westerham (PLZ: 83620)",
        //             b"https://fa-technik-adfc.de/ein?ags=09278121;;str=Beethovenstraße;hsnr=9;n1=Anja;n2=Lekies" => "SR 121: Feldkirchen, Niederbayern (PLZ: 94351)",
        //         ]
        //     ];
        // }

        try {
            if ($url) {
                Log::info("1get " . $url);
                // If I decode the URL as a whole, and call Http::get(url), I get an error
                $urlParts = parse_url($url);
                $queryParams = self::decode($urlParts["query"]);
                $resp = Http::retry(3, 1000)->get("https://fa-technik-adfc.de/ein", $queryParams);
                Log::info("2get " . $url);
            } else {
                Log::info("3get " . $url);
                $resp = Http::retry(3, 1000)->get('https://fa-technik-adfc.de/ein', [
                    'name' => self::decode($this->ort),
                    'str' => self::decode($this->strasse),
                    'hsnr' => $this->hsnr ?? '1',
                    'n1' => self::decode($this->vorname),
                    'n2' => self::decode($this->nachname),
                ]);
                Log::info("4get " . $url);
            }

            if ($resp->failed()) {
                Log::info("5get failed " . $url);
                return ['status' => 'error', 'message' => 'Website nicht erreichbar.'];
            }

            $body = $resp->body();
            $pos = strpos($body, '<big><big>');
            if ($pos !== false) {
                $ein = substr($body, $pos + 25, 16);

                return ['status' => 'success', 'ein' => trim($ein)];
            }

            // Parse alternatives if present
            if (str_contains($body, 'ags=')) {
                preg_match_all('/<a href="([^"]+ags=[^"]+)">([^<]+)<\/a><\/td>\s*<td>([^<]+)/i', $body, $matches, PREG_SET_ORDER);

                $options = [];
                foreach ($matches as $match) {
                    $optionUrl = self::encode(htmlspecialchars_decode($match[1]));
                    if (str_starts_with($optionUrl, '/')) {
                        $optionUrl = 'https://fa-technik-adfc.de' . $optionUrl;
                    }

                    $label = trim($match[2]) . ': ' . trim($match[3]);
                    // Basic cleanup for common ISO-8859-1 garble in UTF-8 environment
                    $label = $this->encode($label); // Simple fix for common German chars if they appear this way

                    $options[$optionUrl] = $label;
                }
                if (!empty($options)) {
                    return ['status' => 'ambiguous', 'options' => $options];
                }
            }
            return ['status' => 'error', 'message' => 'Keine EIN gefunden.'];
        } catch (ConnectionException $e) {
            Log::info("6getex1 " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Timeout beim Laden der EIN.'];
        } catch (Exception $e) {
            Log::info("7getex2 " . $url);
            return ['status' => 'error', 'message' => 'Fehler: ' . $e->getMessage()];
        }
    }

    public function retryEIN(): void
    {
        $result = $this->fetchEIN();
        if ($result['status'] === 'success') {
            $this->update(['ein' => $result['ein']]);
        }
    }

    public static function createBuchung($data): Buchung
    {
        $data['kursnummer'] = $data['termin_id'];
        static::getEIN($data);
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
        if (!str_ends_with($this->email, '@adfc-muenchen.de')) {
            return;
        } // TODO
        $termin = Termin::find($this->termin_id);
        try {
            Mail::to($this->email)->send(new Bestaetigung($termin, $this));
            $this->update(['anmeldebestätigung' => now()]);
        } catch (Throwable $t) {
            Log::error('error ' . $t->getMessage());
            Log::error('error ' . $t);
        }
        static::notifySuccess('Bestätigung versendet');
    }

    public function termin(): BelongsTo
    {
        return $this->belongsTo(static::$kursClass, 'termin_id', 'id');
    }
}
