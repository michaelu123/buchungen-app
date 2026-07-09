<?php

namespace App\Filament\Resources\Technik\Kurse\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Set;

class KursForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('nummer')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('titel')
                    ->required(),
                DatePicker::make('datum')
                    ->native(false)
                    ->locale('de')
                    ->displayFormat("D, d.m")
                    ->required(),
                TextInput::make("uhrzeit")
                    ->mask("99:99 - 99:99")
                    ->placeholder("hh:mm - hh:mm")
                    ->required(),
                TextInput::make('rvp')
                    ->label("URL"),
                TextInput::make('kursplätze')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('restplätze', $state)),
                Hidden::make('restplätze'),
                TextInput::make('leiter')->label("Leiter:in"),
                TextInput::make('leiter2')->label("Leiter:in2"),
                Textarea::make('kommentar'),
            ]);
    }
}
