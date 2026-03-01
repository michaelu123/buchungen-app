<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\RFSF\Buchung;
use App\Models\RFSF\Kurs;

class RFSFSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Kurs::factory()->count(10)->state(
      new Sequence(
        ["nummer" => "RFSF001G", "uhrzeit" => "01:00 - 02:00", "datum" => "2026/01/01", "ersatztermin" => "2026/01/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF002A", "uhrzeit" => "02:00 - 03:00", "datum" => "2026/02/01", "ersatztermin" => "2026/02/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF003S", "uhrzeit" => "03:00 - 04:00", "datum" => "2026/03/01", "ersatztermin" => "2026/03/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF004G", "uhrzeit" => "04:00 - 05:00", "datum" => "2026/04/01", "ersatztermin" => "2026/04/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF005A", "uhrzeit" => "05:00 - 06:00", "datum" => "2026/05/01", "ersatztermin" => "2026/05/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF006S", "uhrzeit" => "06:00 - 07:00", "datum" => "2026/06/01", "ersatztermin" => "2026/06/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF007G", "uhrzeit" => "07:00 - 08:00", "datum" => "2026/07/01", "ersatztermin" => "2026/07/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF008A", "uhrzeit" => "08:00 - 09:00", "datum" => "2026/08/01", "ersatztermin" => "2026/08/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF009S", "uhrzeit" => "09:00 - 10:00", "datum" => "2026/09/01", "ersatztermin" => "2026/09/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSF010G", "uhrzeit" => "10:00 - 11:00", "datum" => "2026/10/01", "ersatztermin" => "2026/10/05", "kursort" => "Radlerhaus", "kursplätze" => 20, "restplätze" => 20],
      )
    )->create();

    Buchung::factory()
      ->count(100)
      ->create();
  }
}
