<?php

namespace App\Models\Codier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

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
                "ort" => "Stadtteilwoche - Berg am Laim ",
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
                "mitgliedsnummer" => "",
            ];

            Buchung::create($b);
        }
    }
}