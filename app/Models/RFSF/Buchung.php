<?php

namespace App\Models\RFSF;

use App\Models\BaseBuchung;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buchung extends BaseBuchung
{
    use HasFactory;

    public static bool $confirmAutomatically = false;
    public static bool $requireEmailVerification = true;
    public static bool $requireAbbuchung = true;

    protected $table = "rfsf_buchungen";

    public function getFrom(): string
    {
        return "radfahrschule_anmeldungen@adfc-muenchen.de";
    }
}
