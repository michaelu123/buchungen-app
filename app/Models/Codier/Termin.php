<?php

namespace App\Models\Codier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Termin extends Model
{
    use HasFactory;
    protected $table = "codier_termine";

    protected $fillable = [
        'notiz',
        'datum',
        'beginn',
        'ende',
        'kommentar',
    ];

    public function buchungen(): HasMany
    {
        return $this->hasMany(Buchung::class, "termin_id", "id");
    }
}

