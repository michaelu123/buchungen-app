<?php

namespace Database\Factories\Technik;

use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Technik\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["TK001", "TK002", "TK003", "TK004", "TK005", "TK006", "TK007", "TK008", "TK009", "TK010"];

  public function getNummern(int $r): string
  {
    return $this->nummern[$r];
  }
}
