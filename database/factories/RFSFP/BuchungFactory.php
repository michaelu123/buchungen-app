<?php

namespace Database\Factories\RFSFP;

use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RFSFP\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSFP001", "RFSFP002", "RFSFP003", "RFSFP004", "RFSFP005", "RFSFP006", "RFSFP007", "RFSFP008", "RFSFP009", "RFSFP010"];

  public function getNummern(int $r): string
  {
    return $this->nummern[$r];
  }
}
