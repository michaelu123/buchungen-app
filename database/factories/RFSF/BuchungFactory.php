<?php

namespace Database\Factories\RFSF;

use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RFSF\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSF001", "RFSF002", "RFSF003", "RFSF004", "RFSF005", "RFSF006", "RFSF007", "RFSF008", "RFSF009", "RFSF010"];

  public function getNummern(int $r): string
  {
    return $this->nummern[$r];
  }
}
