<?php

namespace App\Models\RFSA;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{
    use HasFactory;

    protected $table = "rfsa_kurse";
    protected $fillable = [
        'nummer',
        'notiz',
        'uhrzeit',
        'tag1',
        'tag2',
        'tag3',
        'tag4',
        'tag5',
        'tag6',
        'tag7',
        'tag8',
        'ersatztermin1',
        'ersatztermin2',
        'kursplätze',
        'restplätze',
        'lehrer',
        'co_lehrer',
        'co_lehrer2',
        'hospitant',
        'hospitant2',
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
            $this->tag1 ?? null,
            $this->tag2 ?? null,
            $this->tag3 ?? null,
            $this->tag4 ?? null,
            $this->tag5 ?? null,
            $this->tag6 ?? null,
            $this->tag7 ?? null,
            $this->tag8 ?? null,
        ] : [
            $this->ersatztermin1 ?? null,
            $this->ersatztermin2 ?? null,
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
        return $this->nummer . ": " . $this->uhrzeit . ", " . $this->termine(true) . ", Ersatztermine: " . $this->termine(false);
    }
}
