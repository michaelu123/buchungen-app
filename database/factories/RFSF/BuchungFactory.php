<?php

namespace Database\Factories\RFSF;

use App\Models\RFSF\Kurs;
use Database\Factories\BaseBuchungFactory;

class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSF001G", "RFSF002A", "RFSF003S", "RFSF004G", "RFSF005A", "RFSF006S", "RFSF007G", "RFSF008A", "RFSF009S", "RFSF010G"];

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
