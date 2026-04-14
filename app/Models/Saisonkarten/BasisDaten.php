<?php

namespace App\Models\Saisonkarten;

use Illuminate\Database\Eloquent\Model;

class BasisDaten extends Model
{
    protected $table = "sk_basisdaten";
    protected $fillable = [
        'jahr',
        'offen',
        'sknummer',
        'gueltigab',
        'gueltigbis',
    ];
}
