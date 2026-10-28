<?php

namespace App\Filament\Resources\RFSWartelisten\BasisDaten\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BasisDatenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('rfsa_warteliste')
                    ->label("RFSA Warteliste an/aus")
                    ->required(),
                Textarea::make('text_rfsa')
                    ->label("RFSA Warteliste Zusatztext")
                    ->required(),
                Toggle::make('rfsfg_warteliste')
                    ->label("RFSFG Warteliste an/aus")
                    ->required(),
                Textarea::make('text_rfsfg')
                    ->label("RFSFG Warteliste Zusatztext")
                    ->required(),
                Toggle::make('rfsfa_warteliste')
                    ->label("RFSFA Warteliste an/aus")
                    ->required(),
                Textarea::make('text_rfsfa')
                    ->label("RFSFA Warteliste Zusatztext")
                    ->required(),
                Toggle::make('rfsfs_warteliste')
                    ->label("RFSFS Warteliste an/aus")
                    ->required(),
                Textarea::make('text_rfsfs')
                    ->label("RFSFS Warteliste Zusatztext")
                    ->required(),
                Toggle::make('rfsfp_warteliste')
                    ->label("RFSFP Warteliste an/aus")
                    ->required(),
                Textarea::make('text_rfsfp')
                    ->label("RFSFP Warteliste Zusatztext")
                    ->required(),
            ]);
    }
}
