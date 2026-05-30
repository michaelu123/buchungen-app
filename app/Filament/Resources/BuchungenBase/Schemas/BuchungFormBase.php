<?php

namespace App\Filament\Resources\BuchungenBase\Schemas;

use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

abstract class BuchungFormBase
{
    abstract protected static function getBuchungModelClass(): string;

    abstract protected static function getKursModelClass(): string;

    protected static function selectKurs(): array
    {
        $buchungClass = static::getBuchungModelClass();
        $kursClass = static::getKursModelClass();
        $useTermin = str_contains($kursClass, 'Termin');
        if ($useTermin) {
            // $termine = $kursClass::whereNull('notiz')
            //     ->pluck('datum', 'datum')
            //     ->mapWithKeys(function (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $datum): array {
            //         return [$datum => Carbon::parse($datum)->translatedFormat('D, d.m')];
            //     });
            // return [
            //     Select::make("termin_id")->relationship(name: "termin", titleAttribute: "datum")->label("Datum")->live(),
            //     Select::make("uhrzeit")->options(
            //         function (Get $get, $record) {
            //             return [$record->uhrzeit];
            //         }
            //     ),
            // ];
            $termine = $buchungClass::getTermine();
            $termineOptions = $buchungClass::getTermineOptions($termine);

            return [
                Select::make('termin_id')
                    ->label('Termin')
                    ->belowLabel(fn (): string => $termine->isEmpty()
                        ? 'Leider gibt es aktuell keine freien Termine!'
                        : 'Ich möchte mich für folgenden Termin anmelden:')
                    ->options($termineOptions)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?Model $record) {
                        if ($get('termin_id') == $record->termin_id) {
                            $set('uhrzeit', $record->uhrzeit);
                        } else {
                            $set('uhrzeit', null);
                        }
                    })
                    ->partiallyRenderComponentsAfterStateUpdated(['uhrzeit'])
                    ->required(),
                Select::make('uhrzeit')
                    ->required()
                    ->options(fn (Get $get, Model $record): array => $buchungClass::uhrzeiten(
                        $get('termin_id'),
                        $record->termin_id == $get('termin_id') ? $get('uhrzeit') : '',
                        $termine
                    ))
                    ->unique(
                        $buchungClass,
                        'uhrzeit',
                        modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('termin_id', $get('termin_id'))
                    )
                    ->validationMessages([
                        'unique' => 'Die Uhrzeit wurde inzwischen vergeben, bitte wählen Sie eine andere.',
                        'in' => 'Die Uhrzeit wurde inzwischen vergeben, bitte wählen Sie eine andere.',
                    ])
                    ->label('Uhrzeit'),
            ];

        } else {
            $kurse = $kursClass::whereNull('notiz')
                ->where('restplätze', '>', 0)
                ->get()
                ->mapWithKeys(function ($kurs): array {
                    return [$kurs->id => $kurs->kursDetails()];
                })
                ->all();

            return [
                Select::make('kurs_id')
                    ->label('Kursname')
                    ->placeholder('Wähle einen Kurs')
                    ->options($kurse)
                    ->required(),
            ];
        }
    }

    protected static function abbuchungFelder(): array
    {
        $buchungClass = static::getBuchungModelClass();
        if (! $buchungClass::$requireAbbuchung) {
            return [];
        }

        return [
            TextInput::make('kontoinhaber')
                ->required(),
            TextInput::make('iban')
                ->label('IBAN oder Aktive/er')
                ->rules([
                    fn (): Closure => function ($attribute, $value, Closure $fail) use ($buchungClass): void {
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
            DateTimePicker::make('eingezogen'),
            TextInput::make('betrag')
                ->numeric(),
        ];
    }

    protected static function verificationFelder(): array
    {
        $buchungClass = static::getBuchungModelClass();
        if (! $buchungClass::$requireEmailVerification) {
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
                ...static::selectKurs(),
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
