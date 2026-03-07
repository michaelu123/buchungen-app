<?php

namespace App\Models\Technik;

use App\Mail\Technik\Bestaetigung;
use App\Models\BaseBuchung;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buchung extends BaseBuchung
{
    use HasFactory;

    public static bool $confirmAutomatically = true;
    public static bool $requireEmailVerification = true;
    public static bool $requireAbbuchung = true;
    protected static ?string $kursClass = Kurs::class;
    protected static ?string $bestätigungClass = Bestaetigung::class;

    protected $table = "technik_buchungen";
    public function getFrom(): string
    {
        return "technik_anmeldungen@adfc-muenchen.de";
    }
}
