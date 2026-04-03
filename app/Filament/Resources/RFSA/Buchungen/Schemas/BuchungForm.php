<?php

namespace App\Filament\Resources\RFSA\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;

class BuchungForm extends BuchungFormBase
{
    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getKursModelClass(): string
    {
        return Kurs::class;
    }

    protected static function abbuchungFelder(): array
    {
        $buchungClass = static::getBuchungModelClass();
        if (!$buchungClass::$requireAbbuchung)
            return [];
        return [
            TextInput::make('kontoinhaber')
                ->required(),
            TextInput::make('iban')
                ->label('IBAN oder Aktive/er')
                ->rules([
                    fn(): Closure => function ($attribute, $value, Closure $fail) use ($buchungClass): void {
                        if (!$buchungClass::test_iban($value)) {
                            $fail('Die IBAN ist ungültig.');
                        }
                    },
                ])
                ->required(),
            Checkbox::make('lastschriftok')
                ->label('Lastschrift genehmigt (oder Buchung ungültig!)')
                ->default(true)
                ->required(),
            TextInput::make('ermäßigung'),
            DateTimePicker::make('eingezogen'),
            TextInput::make('betrag')
                ->numeric(),
        ];
    }

}
