<?php

namespace App\Models\RFSWartelisten;

use Illuminate\Database\Eloquent\Model;

class BasisDaten extends Model
{
    protected $table = "rfs_basisdaten";

    protected $fillable = [
        "rfsa_warteliste", // Anfänger
        "text_rfsa",
        "rfsfg_warteliste", // Fahrsicherheit Grundkurs
        "text_rfsfg",
        "rfsfa_warteliste", // Fahrsicherheit Aufbaukurs
        "text_rfsfa",
        "rfsfs_warteliste", // Fahrsicherheit Seniorkurs
        "text_rfsfs",
        "rfsfp_warteliste", // Fahrpraxis 
        "text_rfsfp",
    ];
}
