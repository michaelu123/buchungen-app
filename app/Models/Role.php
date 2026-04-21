<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ["name"];

    const ROLES = [
        'Admin' => 'ADMIN',
        'RFS' => 'RFS',
        'RFSA' => 'RFSA',
        'RFSF' => 'RFSF',
        'RFSFP' => 'RFSFP',
        'Technik' => 'TK',
        'Codier' => 'CODIER',
        'Saisonkarten' => 'SK',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
