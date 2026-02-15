<?php

namespace App\Filament\Resources\RFSA\Kurse\Schemas;

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
                FusedGroup::make([
                    DatePicker::make('tag1')
                        ->placeholder("Tag 1")
                        ->native(false)
                        ->label("Tag 1")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag2')
                        ->placeholder("Tag 2")
                        ->native(false)
                        ->label("Tag 2")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag3')
                        ->placeholder("Tag 3")
                        ->native(false)
                        ->label("Tag 3")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag4')
                        ->placeholder("Tag 4")
                        ->native(false)
                        ->label("Tag 4")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('tag5')
                        ->placeholder("Tag 5")
                        ->native(false)
                        ->label("Tag 5")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag6')
                        ->placeholder("Tag 6")
                        ->native(false)
                        ->label("Tag 6")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag7')
                        ->placeholder("Tag 7")
                        ->native(false)
                        ->label("Tag 7")
                        ->displayFormat("D, d.m"),
                    DatePicker::make('tag8')
                        ->placeholder("Tag 8")
                        ->native(false)
                        ->label("Tag 8")
                        ->displayFormat("D, d.m"),
                ])->label("Kurstermine")->columns(4),
                FusedGroup::make([
                    DatePicker::make('ersatztermin1')
                        ->placeholder("Ersatztermin 1")
                        ->native(false)
                        ->label("Ersatztermin 1")
                        ->displayFormat("D, d.m")
                        ->required(),
                    DatePicker::make('ersatztermin2')
                        ->placeholder("Ersatztermin 2")
                        ->native(false)
                        ->label("Ersatztermin 2")
                        ->displayFormat("D, d.m")
                        ->required(),
                ])->label("Ersatztermine")->columns(2),
                TextInput::make('kursplätze')
                    ->required()
                    ->numeric(),
                TextInput::make('restplätze')
                    ->required()
                    ->numeric(),
                Textarea::make('kommentar'),
                TextInput::make('lehrer'),
                TextInput::make('co_lehrer')->label("Co-Lehrer"),
                TextInput::make('co_lehrer2')->label("Co-Lehrer2"),
                TextInput::make('hospitant'),
                TextInput::make('hospitant2'),
            ]);
    }
}
