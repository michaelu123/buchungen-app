<?php

namespace App\Filament\Resources\BuchungenBase\Schemas;

use Carbon\Carbon;
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

    protected static function select($kursClass): Select
    {
        $useTermin = str_contains($kursClass, 'Termin');
        if ($useTermin) {
            $termine = $kursClass::whereNull('notiz')
                ->pluck('datum', 'datum')
                ->mapWithKeys(function ($datum) {
                    return [$datum => Carbon::parse($datum)->translatedFormat('D, d.m')];
                });
            return Select::make('datum')
                ->label('Termin')
                ->placeholder('Wähle einen Termin')
                ->options($termine)
                ->required();
        } else {
            $kurse = $kursClass::whereNull('notiz')
                ->where('restplätze', '>', 0)
                ->get()
                ->mapWithKeys(function ($kurs): array {
                    return [$kurs->nummer => $kurs->kursDetails()];
                })
                ->all();
            return Select::make('kursnummer')
                ->label('Kursname')
                ->placeholder('Wähle einen Kurs')
                ->options($kurse)
                ->required();
        }
    }

    public static function configure(Schema $schema): Schema
    {
        $buchungClass = static::getBuchungModelClass();
        $kursClass = static::getKursModelClass();

        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->rules('digits:8'),
                static::select($kursClass),
                Select::make('anrede')
                    ->placeholder('Wähle eine Anrede')
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
                DateTimePicker::make('verified')
                    ->label('Email verifiziert'),
                DateTimePicker::make('anmeldebestätigung')
                    ->label('Anmeldebestätigung versendet'),
                DateTimePicker::make('eingezogen'),
                TextInput::make('betrag')
                    ->numeric(),
                Textarea::make('kommentar'),
            ]);
    }
}
