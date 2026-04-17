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

    // Do we want to change Kurs/Termin here? Or just delete the Buchung and make a new one?
    // protected static function select($kursClass): array
    // {
    //     $useTermin = str_contains($kursClass, 'Termin');
    //     if ($useTermin) {
    //         $termine = $kursClass::whereNull('notiz')
    //             ->pluck('datum', 'datum')
    //             ->mapWithKeys(function (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $datum): array {
    //                 return [$datum => Carbon::parse($datum)->translatedFormat('D, d.m')];
    //             });
    //         return [
    //             TextInput::make('termin.datum')
    //                 ->saved(false)
    //                 ->readOnly(),
    //             Select::make("termin_id")->relationship(name: "termin", titleAttribute: "datum")->label("Datum")->live(),
    //             Select::make("uhrzeit")->options(
    //                 function (Get $get, $record) {
    //                     return [$record->uhrzeit];
    //                 }
    //             ),
    //         ];
    //     } else {
    //         $kurse = $kursClass::whereNull('notiz')
    //             ->where('restplätze', '>', 0)
    //             ->get()
    //             ->mapWithKeys(function ($kurs): array {
    //                 return [$kurs->nummer => $kurs->kursDetails()];
    //             })
    //             ->all();
    //         return [
    //             Select::make('kursnummer')
    //                 ->label('Kursname')
    //                 ->placeholder('Wähle einen Kurs')
    //                 ->options($kurse)
    //                 ->required()
    //         ];
    //     }
    // }

    protected static function abbuchungFelder(): array
    {
        $buchungClass = static::getBuchungModelClass();
        if (!$buchungClass::$requireAbbuchung) {
            return [];
        }
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
            DateTimePicker::make('eingezogen'),
            TextInput::make('betrag')
                ->numeric(),
        ];
    }

    protected static function verificationFelder(): array
    {
        $buchungClass = static::getBuchungModelClass();
        if (!$buchungClass::$requireEmailVerification) {
            return [];
        }
        return [
            DateTimePicker::make('verified')
                ->label('Email verifiziert'),
        ];
    }

    protected static function personFelder(): array
    {
        return [
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
            TextInput::make('strasse')
                ->label('Straße')
                ->required(),
            TextInput::make('hsnr')
                ->label('Hausnummer'),
            TextInput::make('telefonnr')
                ->label('Telefon')
                ->tel()
                ->required(),
        ];
    }

    protected static function zusatzFelder(): array
    {
        return [];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->rules('digits:8'),
                // ...static::select($kursClass),
                ...static::personFelder(),
                ...static::abbuchungFelder(),
                ...static::verificationFelder(),
                ...static::zusatzFelder(),
                DateTimePicker::make('anmeldebestätigung')
                    ->label('Anmeldebestätigung versendet'),
                Textarea::make('kommentar'),
            ]);
    }
}
