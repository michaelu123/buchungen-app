<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class TechnikKurs extends Model
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
        return $this->hasMany(TechnikBuchung::class, "kursnummer", "nummer");
    }
}
