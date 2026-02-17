<?php

namespace App\Models\Technik;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{
    use HasFactory;


    protected $table = "technik_kurse";
    protected $fillable = [
        'nummer',
        'notiz',
        'titel',
        "datum",
        'kursplätze',
        'restplätze',
        'leiter',
        'leiter2',
        "kommentar",
    ];

    public function getRouteKeyName(): string
    {
        return 'nummer';
    }

    public function buchungen(): HasMany
    {
        return $this->hasMany(Buchung::class, "kursnummer", "nummer");
    }
    public function kursDetails(): string
    {
        return $this->nummer . ": " . $this->titel . " am " . Carbon::parse($this->datum)->translatedFormat('D, d.m');
    }
}
