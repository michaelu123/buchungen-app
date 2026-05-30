<?php

namespace Database\Factories\RFSFP;

use App\Models\RFSFP\Kurs;
use Database\Factories\BaseBuchungFactory;

class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSFP001", "RFSFP002", "RFSFP003", "RFSFP004", "RFSFP005", "RFSFP006", "RFSFP007", "RFSFP008", "RFSFP009", "RFSFP010"];

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
