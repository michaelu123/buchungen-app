<?php

namespace App\Models\Technik;

use App\Models\BaseBuchung;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buchung extends BaseBuchung
{
    use HasFactory;

    protected bool $confirmAutomatically = false;
    protected $table = "technik_buchungen";
    public function getFrom(): string
    {
        return "technik_anmeldungen@adfc-muenchen.de";
    }
}
