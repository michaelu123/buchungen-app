<?php

namespace App\Filament\Resources\BuchungenBase\Schemas;

use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

abstract class BuchungFormBase
{
    abstract protected static function getBuchungModelClass(): string;

    abstract protected static function getKursModelClass(): string;

    public static function configure(Schema $schema): Schema
    {
        $buchungClass = static::getBuchungModelClass();
        $kursClass = static::getKursModelClass();

        $kurse = $kursClass::whereNull('notiz')
            ->where('restplätze', '>', 0)
            ->get()
            ->mapWithKeys(function ($kurs) {
                return [$kurs['nummer'] => $kurs->kursDetails()];
            })
            ->all();

        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->rules('digits:8'),
                Select::make('kursnummer')
                    ->label('Kursname')
                    ->options($kurse)
                    ->required(),
                Select::make('anrede')
                    ->options(['Herr' => 'Herr', 'Frau' => 'Frau', '' => 'Keine Angabe']),
                TextInput::make('vorname')
                    ->required(),
                TextInput::make('nachname')
                    ->required(),
                TextInput::make('postleitzahl')
                    ->required()
                    ->numeric(),
                TextInput::make('ort')
                    ->required(),
                TextInput::make('strasse_nr')
                    ->label('Straße und Hausnummer')
                    ->required(),
                TextInput::make('telefonnr')
                    ->label('Telefon')
                    ->tel()
                    ->required(),
                TextInput::make('kontoinhaber')
                    ->required(),
                TextInput::make('iban')
                    ->rules([
                        fn (): Closure => function ($attribute, $value, Closure $fail) use ($buchungClass) {
                            if (! $buchungClass::test_iban($value)) {
                                $fail('Die IBAN ist ungültig.');
                            }
                        },
                    ])
                    ->required(),
                Checkbox::make('lastschriftok')
                    ->label('Lastschrift genehmigt (oder Buchung ungültig!)')
                    ->default(true)
                    ->required(),
                DateTimePicker::make('verified')
                    ->label('Email verifiziert'),
                DateTimePicker::make('eingezogen'),
                TextInput::make('betrag')
                    ->numeric(),
                Textarea::make('kommentar'),
            ]);
    }
}
