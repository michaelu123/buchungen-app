<?php

namespace App\Models\Technik;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{
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
}
