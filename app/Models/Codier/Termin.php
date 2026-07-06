<?php

namespace App\Models\Codier;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

class Termin extends Model
{
    use HasFactory;
    protected $table = "codier_termine";

    protected $fillable = [
        'notiz',
        'datum',
        'ort',
        'rvp',
        'beginn',
        'ende',
        'kommentar',
    ];

    public function buchungen(): HasMany
    {
        return $this->hasMany(Buchung::class, "termin_id", "id");
    }

    public static function importOldCodier($path)
    {
        $exiTermineMap = Termin::all()->keyBy('datum')->toArray();
        $exiBuchungenMap = Buchung::with('termin')->get()
            ->keyBy(fn(Buchung $b): string => "{$b->termin->datum} {$b->uhrzeit}:00")
            ->toArray();

        // currdir = public
        $dbStr = file_get_contents($path);
        $dbArr = json_decode($dbStr);
        $buchungen = [];
        $termine = [];
        foreach ($dbArr as $table) {
            if ($table->type != "table") {
                continue;
            }
            if ($table->name == "fahrradcodierung_muenchen") {
                $dataArr = $table->data;
                foreach ($dataArr as $data) {
                    if (
                        $data->blocked != "1"
                        || $data->active != "1"
                        || !$data->create_time
                        || !$data->first_name
                        || !$data->last_name
                        || !$data->street
                        || !$data->housenumber
                        || !$data->zipcode
                        || !$data->city
                        || !$data->mailadress
                    ) {
                        continue;
                    }
                    $buchungen[] = $data;
                }
            }
            if ($table->name == "fahrradcodierung_muenchen_termine") {
                $termine = $table->data;
            }
        }
        // $x = json_encode(["buchungen" => $buchungen, "termine" => $termine], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        // file_put_contents("../xxx/codierungstermine.json", $x);

        foreach ($termine as $termin) {
            if (isset($exiTermineMap[$termin->datum])) {
                continue;
            }
            $t = [
                "ort" => $termin->ort,
                "datum" => $termin->datum,
                "beginn" => substr($termin->startzeit, 11, 5),
                "ende" => substr($termin->endzeit, 11, 5),
            ];
            $exiTermineMap[$termin->datum] = Termin::create($t)->toArray();
        }

        foreach ($buchungen as $buchung) {
            $t = $exiTermineMap[substr($buchung->date, 0, 10)] ?? null;
            if (!$t) {
                dd($buchung->date);
            }
            $b = $exiBuchungenMap[$buchung->date] ?? null;
            $notiz = null;
            if ($b) {
                if (
                    $buchung->first_name != $b["vorname"]
                    || $buchung->last_name != $b["nachname"]
                    || $buchung->city != $b["ort"]
                    || $buchung->street != $b["strasse"]
                    || $buchung->housenumber != $b["hsnr"]
                ) {
                    $notiz = "doppelte Uhrzeit";
                } else {
                    continue;
                }
            }
            $b = [
                "notiz" => $notiz,
                "termin_id" => $t["id"],
                "uhrzeit" => substr($buchung->date, 11, 5),
                "anrede" => "",
                "vorname" => $buchung->first_name,
                "nachname" => $buchung->last_name,
                "postleitzahl" => $buchung->zipcode,
                "ort" => $buchung->city,
                "strasse" => $buchung->street,
                "hsnr" => $buchung->housenumber,
                "telefonnr" => $buchung->phone,
                "ein" => $buchung->code,
                "email" => $buchung->mailadress,
                "mitgliedsnummer" => null,
            ];

            Buchung::create($b);
        }
    }

    public static function loadRvp()
    {
        $today = now()->format('Y-m-d');
        $url = "https://api-touren-termine.adfc.de/api/eventItems/search?limit=10000&includedTags=6&eventType=Termin&unitKey=152059&includeSubsidiary=true&beginning=" . $today;
        $resp = Http::get($url);
        $res = $resp->json();
        $items = $res["items"];
        foreach ($items as $item) {
            if ($item["cStatus"] != "Published") {
                continue;
            }
            $terminData = [];
            $beginning = $item["beginning"]; // unfortunately in UTC! 2026-06-27T09:30:00+00:00
            $tsb1 = Carbon::parse($beginning, 'UTC');
            $tsb2 = $tsb1->setTimezone("Europe/Berlin");
            $tsb3 = $tsb2->translatedFormat("Y-m-d H:i");
            $terminData["datum"] = substr($tsb3, 0, 10);
            $terminData["beginn"] = substr($tsb3, 11);
            $end = $item["end"];
            $tse1 = Carbon::parse($end, 'UTC');
            $tse2 = $tse1->setTimezone("Europe/Berlin");
            $tse3 = $tse2->translatedFormat("Y-m-d H:i");
            $terminData["ende"] = substr($tse3, 11);
            $terminData["ort"] = $item["startLocation"];
            $terminData["rvp"] = "https://touren-termine.adfc.de/radveranstaltung/" . $item["cSlug"];

            if (Termin::where('rvp', $terminData["rvp"])->first()) {
                continue;
            }
            (new Termin($terminData))->save();
        }
    }
}