<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Technik\Buchung;
use App\Models\Technik\Kurs;

class TechnikSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Kurs::factory()->count(10)->state(
      new Sequence(
        ["nummer" => "TK001", "titel" => "Titel1", "datum" => "2026/01/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK002", "titel" => "Titel2", "datum" => "2026/02/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK003", "titel" => "Titel3", "datum" => "2026/03/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK004", "titel" => "Tite14", "datum" => "2026/04/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK005", "titel" => "Titel5", "datum" => "2026/05/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK006", "titel" => "Titel6", "datum" => "2026/06/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK007", "titel" => "Titel7", "datum" => "2026/07/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK008", "titel" => "Titel8", "datum" => "2026/08/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK009", "titel" => "Titel9", "datum" => "2026/09/01", "kursplätze" => 20, "restplätze" => 20],
        ["nummer" => "TK010", "titel" => "Titel10", "datum" => "2026/10/01", "kursplätze" => 20, "restplätze" => 20],
      )
    )->create();

    Buchung::factory()
      ->count(100)
      ->create();
  }
}
