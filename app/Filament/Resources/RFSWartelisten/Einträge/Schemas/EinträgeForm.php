<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EinträgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kursart')
                    ->placeholder('Wähle eine Kursart')
                    ->options([
                        'Anfängerkurse' => 'Anfängerkurse',
                        'Fahrsicherheitstraining-Grundkurse' =>
                            'Fahrsicherheitstraining-Grundkurse',
                        'Fahrsicherheitstraining-Aufbaukurse' =>
                            'Fahrsicherheitstraining-Aufbaukurse',
                        'Fahrsicherheitstraining-Senior:innenkurse' =>
                            'Fahrsicherheitstraining-Senior:innenkurse',
                        'Fahrpraxiskurse' =>
                            'Fahrpraxiskurse',
                    ])
                    ->required(),
                TextInput::make('email')
                    ->required(),
                Select::make('anrede')
                    ->placeholder('Wähle eine Anrede')
                    ->options(['Herr' => 'Herr', 'Frau' => 'Frau', '' => 'Keine Angabe']),
                TextInput::make('vorname')
                    ->required(),
                TextInput::make('nachname')
                    ->required(),
                TextInput::make('telefonnr')
                    ->label('Telefon')
                    ->required(),
                Textarea::make('mitteilung'),
                Textarea::make('kommentar'),
            ]);
    }
}
