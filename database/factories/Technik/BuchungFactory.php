<?php

namespace Database\Factories\Technik;

use App\Models\Technik\Kurs;
use Database\Factories\BaseBuchungFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Technik\Buchung>
 */
class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["TK001", "TK002", "TK003", "TK004", "TK005", "TK006", "TK007", "TK008", "TK009", "TK010"];

  public $kurse_ids = [];

  public function __construct(...$args)
  {
    parent::__construct(...$args);
    $this->kurse_ids = Kurs::whereIn("nummer", $this->nummern)->pluck("id")->toArray();
  }


  public function getNummern(int $r): string
  {
    return $this->kurse_ids[$r];
  }
}
