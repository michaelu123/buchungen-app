<?php

namespace Database\Factories\RFSA;

use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RFSA\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSA001", "RFSA002", "RFSA003", "RFSA004", "RFSA005", "RFSA006", "RFSA007", "RFSA008", "RFSA009", "RFSA010"];

  public function getNummern(int $r): string
  {
    return $this->nummern[$r];
  }
}
