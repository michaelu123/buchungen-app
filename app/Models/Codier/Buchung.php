<?php

namespace App\Models\Codier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buchung extends Model
{
    use HasFactory;

    protected $fillable = [
        'termin',
        'uhrzeit',
        'anrede',
        'vorname',
        'nachname',
        'postleitzahl',
        'ort',
        'strasse_nr',
        'telefonnr',
        'email',
        'mitgliedsnummer',
        'notiz',
        'anmeldebestätigung',
        'kommentar',
        'created_at',
    ];


    public static bool $confirmAutomatically = true;
    public static bool $requireEmailVerification = false;
    public static bool $requireAbbuchung = false;

    protected $table = "codier_buchungen";
    public function getFrom(): string
    {
        return "anmeldungen-codierung@adfc-muenchen.de";
    }

    public static function createBuchung($data): Buchung
    {
        $buchung = Buchung::create($data);
        return $buchung;
    }
}
