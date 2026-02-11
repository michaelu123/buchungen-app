<?php

namespace App\Filament\Resources\Technik\Buchungen\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Closure;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;

class BuchungForm
{
    public static function configure(Schema $schema): Schema
    {
        $arr1 = Kurs::select(["nummer", "titel"])
            ->whereNull("notiz")
            ->where("restplätze", ">", 0)
            ->get()->toArray();
        $arr2 = collect($arr1)->mapWithKeys(function (array $item) {
            return [$item["nummer"] => $item["nummer"] . " - " . $item["titel"]];
        })->all();
        // dd($arr1, $arr2);
        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->rules("digits:8"),
                Select::make('kursnummer')
                    ->label("Kursname")
                    ->options(
                        $arr2,
                    )
                    ->required(),
                Select::make('anrede')
                    ->options(["Herr" => "Herr", "Frau" => "Frau", "" => "Keine Angabe"]),
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
                        fn(): Closure => function ($attribute, $value, Closure $fail) {
                            if (!Buchung::test_iban($value)) {
                                $fail('Die IBAN ist ungültig.');
                            }
                        },
                    ])
                    ->required(),
                Toggle::make('lastschriftok')
                    ->label('Lastschrift genehmigt (oder Buchung ungültig!)')
                    ->default(true)
                    ->required(),
                DateTimePicker::make('verified')
                    ->label("Email verifiziert"),
                DateTimePicker::make('eingezogen'),
                TextInput::make('betrag')
                    ->numeric(),
            ]);
    }
}
