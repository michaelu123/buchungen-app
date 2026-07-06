<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class BaseKurs extends Model
{
  public static function loadRvp(string $datumName, int $tag)
  {
    $today = now()->format('Y-m-d');
    $url = "https://api-touren-termine.adfc.de/api/eventItems/search?limit=10000&includedTags={$tag}&eventType=Termin&unitKey=152059&includeSubsidiary=true&beginning=" . $today;
    $resp = Http::get($url);
    $res = $resp->json();
    $items = $res["items"];
    foreach ($items as $item) {
      if ($item["cStatus"] != "Published") {
        continue;
      }
      $beginning = $item["beginning"]; // unfortunately in UTC! 2026-06-27T09:30:00+00:00
      $tsb1 = Carbon::parse($beginning, 'UTC');
      $tsb2 = $tsb1->setTimezone("Europe/Berlin");
      $tsb3 = $tsb2->translatedFormat("Y-m-d H:i");
      $datum = substr($tsb3, 0, 10);
      $beginn = substr($tsb3, 11);
      $end = $item["end"];
      $tse1 = Carbon::parse($end, 'UTC');
      $tse2 = $tse1->setTimezone("Europe/Berlin");
      $tse3 = $tse2->translatedFormat("Y-m-d H:i");
      $ende = substr($tse3, 11);
      $rvp = "https://touren-termine.adfc.de/radveranstaltung/" . $item["cSlug"];

      $exi = static::where($datumName, $datum)
        ->where("uhrzeit", "{$beginn} - {$ende}")
        ->first();
      if ($exi && $exi->rvp != $rvp) {
        $exi->update(["rvp" => $rvp]);
      }
    }
  }
}
/*

select * from "rfsa_kurse" where "tag1" = '2026-08-03' and "uhrzeit" = '17:00 - 19:00' limit 1
select * from "rfsa_kurse" where "tag1" = '2026-08-21' and "uhrzeit" = '14:00 - 17:00' limit 1
select * from "rfsa_kurse" where "tag1" = '2026-09-16' and "uhrzeit" = '17:30 - 19:30' limit 1
select * from "rfsa_kurse" where "tag1" = '2026-09-18' and "uhrzeit" = '13:00 - 16:00' limit 1
select * from "rfsa_kurse" where "tag1" = '2026-09-27' and "uhrzeit" = '13:00 - 16:00' limit 1
​select * from "rfsa_kurse" where "tag1" = '2026-10-17' and "uhrzeit" = '09:00 - 12:00' limit 1
​select * from "rfsa_kurse" where "tag1" = '2026-10-17' and "uhrzeit" = '13:00 - 16:00' limit 1
*/