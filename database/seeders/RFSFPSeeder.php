<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;

class RFSFPSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Kurs::factory()->count(10)->state(
      new Sequence(
        ["nummer" => "RFSFP001", "uhrzeit" => "01:00 - 02:00", "datum" => "2026/01/01", "ersatztermin" => "2026/01/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP002", "uhrzeit" => "02:00 - 03:00", "datum" => "2026/02/01", "ersatztermin" => "2026/02/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP003", "uhrzeit" => "03:00 - 04:00", "datum" => "2026/03/01", "ersatztermin" => "2026/03/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP004", "uhrzeit" => "04:00 - 05:00", "datum" => "2026/04/01", "ersatztermin" => "2026/04/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP005", "uhrzeit" => "05:00 - 06:00", "datum" => "2026/05/01", "ersatztermin" => "2026/05/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP006", "uhrzeit" => "06:00 - 07:00", "datum" => "2026/06/01", "ersatztermin" => "2026/06/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP007", "uhrzeit" => "07:00 - 08:00", "datum" => "2026/07/01", "ersatztermin" => "2026/07/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP008", "uhrzeit" => "08:00 - 09:00", "datum" => "2026/08/01", "ersatztermin" => "2026/08/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP009", "uhrzeit" => "09:00 - 10:00", "datum" => "2026/09/01", "ersatztermin" => "2026/09/05", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSFP010", "uhrzeit" => "10:00 - 11:00", "datum" => "2026/10/01", "ersatztermin" => "2026/10/05", "kursplätze" => 20, "restplätze" => 20],
      )
    )->create();

    Buchung::factory()
      ->count(100)
      ->create();
  }
}
