<?php

namespace App\Models\RFSA;

use App\Models\BaseBuchung;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buchung extends BaseBuchung
{
    use HasFactory;
    protected bool $confirmAutomatically = false;

    protected $table = "rfsa_buchungen";

    public function getFrom(): string
    {
        return "radfahrschule_anmeldungen@adfc-muenchen.de";
    }
}
