<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class BaseKurs extends Model
{
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

      $exi = static::where('datum', $terminData["datum"])
        ->where("beginn", $terminData["beginn"])
        ->first();
      if ($exi) {
        if ($exi->rvp != $terminData["rvp"]) {
          $exi->update(["rvp" => $terminData["rvp"]]);
        }
      } else {
        (new static($terminData))->save();
      }
    }
  }
}
