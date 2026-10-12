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
      if ($exi && !$exi->rvp) {
        $exi->update(["rvp" => $rvp]);
      }
    }
  }



  // Eloquent Accessor
  public function getAbgebuchtAttribute(): string
  {
    $buchungen = $this->buchungen;
    $abgebucht = 0;
    $total = 0;
    foreach ($buchungen as $b) {
      if ($b->notiz) {
        continue;
      }
      $total++;
      if ($b->eingezogen) {
        $abgebucht++;
      }
    }
    $color = " ";
    if ($total == 0) {
      return "0WKeine Buchungen";
    }
    if ($abgebucht == $total) {
      $color = "1G";
    } else if ($abgebucht == 0) {
      $color = "3R";
    } else {
      $color = "2Y";
    }
    return $color . $abgebucht . "/" . $total . " (" . (int) ($abgebucht * 100 / $total) . "%)";
  }
}
