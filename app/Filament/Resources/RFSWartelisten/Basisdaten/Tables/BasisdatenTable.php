<?php

namespace App\Filament\Resources\RFSWartelisten\BasisDaten\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BasisDatenTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('rfsa_warteliste')
                    ->label("RFSA Warteliste ist an")
                    ->boolean(),
                TextColumn::make('text_rfsa')
                    ->label("RFSA Warteliste Zusatztext")
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                IconColumn::make('rfsfg_warteliste')
                    ->label("RFSFG Warteliste ist an")
                    ->boolean(),
                TextColumn::make('text_rfsfg')
                    ->label("RFSFG Warteliste Zusatztext")
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                IconColumn::make('rfsfa_warteliste')
                    ->label("RFSFA Warteliste ist an")
                    ->boolean(),
                TextColumn::make('text_rfsfa')
                    ->label("RFSFA Warteliste Zusatztext")->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                IconColumn::make('rfsfs_warteliste')
                    ->label("RFSFS Warteliste ist an")
                    ->boolean(),
                TextColumn::make('text_rfsfs')
                    ->label("RFSFS Warteliste Zusatztext")->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                IconColumn::make('rfsfp_warteliste')
                    ->label("RFSFP Warteliste ist an")
                    ->boolean(),
                TextColumn::make('text_rfsfp')
                    ->label("RFSFP Warteliste Zusatztext")->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
