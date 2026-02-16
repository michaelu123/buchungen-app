<?php

namespace App\Filament\Resources\RFSF\Kurse\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\FusedGroup;

class KursForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notiz'),
                TextInput::make('nummer')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabledOn("edit"),
                TextInput::make("uhrzeit")
                    ->mask("99:99 - 99:99")
                    ->placeholder("hh:mm - hh:mm")
                    ->required(),
                DatePicker::make('datum')
                    ->native(false)
                    ->displayFormat("D, d.m")
                    ->required(),
                DatePicker::make('ersatztermin')
                    ->native(false)
                    ->displayFormat("D, d.m")
                    ->required(),
                TextInput::make('kursort')
                    ->required(),
                TextInput::make('kursplätze')
                    ->required()
                    ->numeric(),
                TextInput::make('restplätze')
                    ->required()
                    ->numeric(),
                Textarea::make('kommentar'),
                TextInput::make('trainer')->label("Trainer:in"),
                TextInput::make('co_trainer')->label("Co-Trainer:in"),
                TextInput::make('hospitant')->label("Hospitant:in"),
                TextInput::make('liste_verschicken')->label("Liste verschicken"),
                TextInput::make('abgesagt_am')->label("Abgesagt am"),
                TextInput::make('abgesagt_wg')->label("Abgesagt wegen"),
                TextInput::make('status')->label("Status"),
            ]);
    }
}
