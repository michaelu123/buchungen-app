<?php

namespace App\Models\RFSFP;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{
    use HasFactory;


    protected $table = "rfsfp_kurse";
    protected $fillable = [
        'nummer',
        'notiz',
        'uhrzeit',
        // 'kursort',
        'datum',
        'ersatztermin',
        'kursplätze',
        'restplätze',
        'trainer',
        'co_trainer',
        'hospitant',
        'liste_verschicken',
        'abgesagt_am',
        'abgesagt_wg',
        'status',
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

    public function termine($tage): string
    {

        $tags = array_filter($tage ? [
            $this->datum ?? null,
        ] : [
            $this->ersatztermin ?? null,
        ], fn($v): bool => !empty($v));

        $formatted = array_map(function (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $t): string {
            try {
                return Carbon::parse($t)->translatedFormat('D, d.m');
            } catch (\Throwable $e) {
                return (string) $t;
            }
        }, $tags);

        return implode(', ', $formatted);
    }

    public function kursDetails(): string
    {
        return $this->nummer . ": " . $this->uhrzeit . ", " . $this->termine(true) . ", Ersatztermin: " . $this->termine(false) . ", Kursort: " . $this->kursort;
    }

    public function ebicsData(): array
    {
        return [
            "mitgliederpreis" => 20,
            "nichtmitgliederpreis" => 35,
            "mandat" => "M-RFSFP-" . now()->year,
        ];
    }
}
