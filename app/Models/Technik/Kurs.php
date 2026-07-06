<?php

namespace App\Models\Technik;

use App\Models\BaseKurs;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurs extends BaseKurs
{
    use HasFactory;


    protected $table = "technik_kurse";
    protected $fillable = [
        'nummer',
        'notiz',
        'titel',
        "datum",
        'uhrzeit',
        'kursplätze',
        'restplätze',
        'rvp',
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
        return $this->hasMany(Buchung::class, "kurs_id", "id");
    }
    public function kursDetails(): string
    {
        $datum = Carbon::parse($this->datum)->translatedFormat('D, d.m');
        return "{$this->nummer}: {$this->titel} am {$datum}, {$this->uhrzeit}";
    }

    public function ebicsData(Buchung $buchung): array
    {
        return [
            $buchung->mitgliedsnummer ? 10 : 20,
            "M-TK-" . now()->year,
            "ADFC Technikkurs",
        ];
    }
}
