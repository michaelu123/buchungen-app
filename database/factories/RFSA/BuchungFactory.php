<?php

namespace Database\Factories\RFSA;

use App\Models\RFSA\Kurs;
use Database\Factories\BaseBuchungFactory;

class BuchungFactory extends BaseBuchungFactory
{
  public $nummern = ["RFSA001", "RFSA002", "RFSA003", "RFSA004", "RFSA005", "RFSA006", "RFSA007", "RFSA008", "RFSA009", "RFSA010"];

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
