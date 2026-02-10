<?php

namespace App\Filament\Resources\Technik\Kurse\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class TechnikKursForm
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
                    ->required(),
                TextInput::make('kursplätze')
                    ->required()
                    ->numeric(),
                TextInput::make('restplätze')
                    ->required()
                    ->numeric(),
                TextInput::make('leiter')
                    ->required(),
                TextInput::make('leiter2'),
            ]);
    }
}
