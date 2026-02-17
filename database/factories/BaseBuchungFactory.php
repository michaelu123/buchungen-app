<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

abstract class BaseBuchungFactory extends Factory
{
  public abstract function getNummern(int $r): string;


  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $r = fake()->randomDigit();
    return [
      "notiz" => fake()->optional($weight = 0.1)->word(),
      "email" => fake()->email(),
      "mitgliedsnummer" => fake()->randomNumber(8, true),
      "kursnummer" => $this->getNummern($r),
      "anrede" => $r < 5 ? "Frau" : "Herr",
      "vorname" => fake()->firstName($r < 5 ? "female" : ($r < 9 ? "male" : null)),
      "nachname" => fake()->lastName,
      "postleitzahl" => fake()->postcode,
      "ort" => fake()->city,
      "strasse_nr" => fake()->streetAddress,
      "telefonnr" => fake()->phoneNumber,
      "kontoinhaber" => fake()->name,
      "iban" => fake()->bankAccountNumber(),
      "kommentar" => fake()->optional($weight = 0.1)->text(120),
      "lastschriftok" => true,
      "verified" => fake()->dateTimeBetween("2025-01-01 00:00:00", "2025-12-31 23:59:59"),
    ];
  }
}
