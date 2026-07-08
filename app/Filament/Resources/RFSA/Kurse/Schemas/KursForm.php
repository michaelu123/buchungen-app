<?php

namespace App\Filament\Resources\RFSA\Kurse\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\FusedGroup;
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
                TextInput::make("uhrzeit")
                    ->mask("99:99 - 99:99")
                    ->placeholder("hh:mm - hh:mm")
                    ->required(),
                FusedGroup::make([
                    DatePicker::make('tag1')
                        ->placeholder("Tag 1")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 1")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag2')
                        ->placeholder("Tag 2")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 2")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag3')
                        ->placeholder("Tag 3")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 3")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag4')
                        ->placeholder("Tag 4")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 4")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag5')
                        ->placeholder("Tag 5")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 5")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag6')
                        ->placeholder("Tag 6")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 6")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag7')
                        ->placeholder("Tag 7")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 7")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag8')
                        ->placeholder("Tag 8")
                        ->native(false)
                        ->locale('de')
                        ->label("Tag 8")
                        ->displayFormat("D, d.m"),
                ])->label("Kurstermine")->columns(4),
                FusedGroup::make([
                    DatePicker::make('ersatztermin1')
                        ->placeholder("Ersatztermin 1")
                        ->native(false)
                        ->locale('de')
                        ->label("Ersatztermin 1")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('ersatztermin2')
                        ->placeholder("Ersatztermin 2")
                        ->native(false)
                        ->locale('de')
                        ->label("Ersatztermin 2")
                        ->displayFormat("D, d.m")
                        ->required(),
                ])->label("Ersatztermine")->columns(2),
                TextInput::make('rvp')
                    ->label("URL"),
                TextInput::make('kursplätze')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('restplätze', $state)),
                Hidden::make('restplätze'),
                Textarea::make('kommentar'),
                TextInput::make('lehrer')->label("Lehrer:in"),
                TextInput::make('co_lehrer')->label("Co-Lehrer:in"),
                TextInput::make('co_lehrer2')->label("Co-Lehrer:in2"),
                TextInput::make('hospitant')->label("Hospitant:in"),
                TextInput::make('hospitant2')->label("Hospitant:in2"),
                TextInput::make('liste_verschicken')->label("Liste verschicken"),
                TextInput::make('abgesagt_am')->label("Abgesagt am"),
                TextInput::make('abgesagt_wg')->label("Abgesagt wegen"),
                TextInput::make('status')->label("Status"),
            ]);
    }
}
