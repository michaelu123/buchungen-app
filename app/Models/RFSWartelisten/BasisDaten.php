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
        "rfsfa_Warteliste", // Fahrsicherheit Aufbaukurs
        "text_rfsfa",
        "rfsfs_Warteliste", // Fahrsicherheit Seniorkurs
        "text_rfsfs",
        "rfsfp_Warteliste", // Fahrpraxis 
        "text_rfsfp",
    ];
}
