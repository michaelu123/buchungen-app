<?php

namespace Database\Factories\RFSF;

use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RFSF\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSF001G", "RFSF002A", "RFSF003S", "RFSF004G", "RFSF005A", "RFSF006S", "RFSF007G", "RFSF008A", "RFSF009S", "RFSF010G"];

  public function getNummern(int $r): string
  {
    return $this->nummern[$r];
  }
}
