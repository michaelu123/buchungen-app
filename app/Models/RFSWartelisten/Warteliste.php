<?php

namespace App\Models\RFSWartelisten;

use Illuminate\Database\Eloquent\Model;

class Warteliste extends Model
{
    protected $table = "rfs_warteliste";

    protected $fillable = [
        'created_at',
        'kursart',
        'email',
        'anrede',
        'vorname',
        'nachname',
        'telefonnr',
        'mitteilung',
        'kommentar',
    ];
}
