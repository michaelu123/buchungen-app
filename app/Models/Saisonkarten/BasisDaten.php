<?php

namespace App\Models\Saisonkarten;

use Illuminate\Database\Eloquent\Builder;
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

    public function buchungen(): Builder
    {
        return Buchung::query();
    }

    public function fillForm(): array
    {
        $schonEingezogen = 0;
        $nochZuEinziehen = 0;
        $unverifiziert = 0;

        $buchungen = Buchung::all();
        foreach ($buchungen as $buchung) {
            // dd($buchung->notiz, !$buchung->lastschriftok, !$buchung->iban, !$buchung->verified, $buchung->eingezogen);
            if ($buchung->notiz || !$buchung->lastschriftok || !$buchung->iban) {
                continue;
            }
            if ($buchung->eingezogen) {
                $schonEingezogen++;
            } else {
                $nochZuEinziehen++;
            }
            if (!$buchung->verified) {
                $unverifiziert++;
            }
        }
        return [
            'eingezogen1' => $schonEingezogen,
            'eingezogen2' => $nochZuEinziehen,
            'unverifiziert' => $unverifiziert,
        ];

    }

    public function ebicsData(Buchung $buchung): array
    {
        return [
            22,
            "M-SK-" . now()->year,
        ];
    }

}
