<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;

class RFSASeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Kurs::factory()->count(10)->state(
      new Sequence(
        ["nummer" => "RFSA001", "uhrzeit" => "01:00 - 02:00", "tag1" => "2026/01/01", "tag2" => "2026/01/02", "tag3" => "2026/01/03", "tag4" => "2026/01/04", "ersatztermin1" => "2026/01/05", "ersatztermin2" => "2026/01/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA002", "uhrzeit" => "02:00 - 03:00", "tag1" => "2026/02/01", "tag2" => "2026/02/02", "tag3" => "2026/02/03", "tag4" => "2026/02/04", "ersatztermin1" => "2026/02/05", "ersatztermin2" => "2026/02/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA003", "uhrzeit" => "03:00 - 04:00", "tag1" => "2026/03/01", "tag2" => "2026/03/02", "tag3" => "2026/03/03", "tag4" => "2026/03/04", "ersatztermin1" => "2026/03/05", "ersatztermin2" => "2026/03/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA004", "uhrzeit" => "04:00 - 05:00", "tag1" => "2026/04/01", "tag2" => "2026/04/02", "tag3" => "2026/04/03", "tag4" => "2026/04/04", "ersatztermin1" => "2026/04/05", "ersatztermin2" => "2026/04/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA005", "uhrzeit" => "05:00 - 06:00", "tag1" => "2026/05/01", "tag2" => "2026/05/02", "tag3" => "2026/05/03", "tag4" => "2026/05/04", "ersatztermin1" => "2026/05/05", "ersatztermin2" => "2026/05/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA006", "uhrzeit" => "06:00 - 07:00", "tag1" => "2026/06/01", "tag2" => "2026/06/02", "tag3" => "2026/06/03", "tag4" => "2026/06/04", "ersatztermin1" => "2026/06/05", "ersatztermin2" => "2026/06/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA007", "uhrzeit" => "07:00 - 08:00", "tag1" => "2026/07/01", "tag2" => "2026/07/02", "tag3" => "2026/07/03", "tag4" => "2026/07/04", "ersatztermin1" => "2026/07/05", "ersatztermin2" => "2026/07/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA008", "uhrzeit" => "08:00 - 09:00", "tag1" => "2026/08/01", "tag2" => "2026/08/02", "tag3" => "2026/08/03", "tag4" => "2026/08/04", "ersatztermin1" => "2026/08/05", "ersatztermin2" => "2026/08/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA009", "uhrzeit" => "09:00 - 10:00", "tag1" => "2026/09/01", "tag2" => "2026/09/02", "tag3" => "2026/09/03", "tag4" => "2026/09/04", "ersatztermin1" => "2026/09/05", "ersatztermin2" => "2026/09/06", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "RFSA010", "uhrzeit" => "10:00 - 11:00", "tag1" => "2026/10/01", "tag2" => "2026/10/02", "tag3" => "2026/10/03", "tag4" => "2026/10/04", "ersatztermin1" => "2026/10/05", "ersatztermin2" => "2026/10/06", "kursplätze" => 20, "restplätze" => 20],
      )
    )->create();

    Buchung::factory()
      ->count(100)
      ->create();
  }
}
