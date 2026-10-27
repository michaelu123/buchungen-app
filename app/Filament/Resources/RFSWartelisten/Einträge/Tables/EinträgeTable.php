<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EinträgeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort("created_at", "desc")
            ->columns([
                TextColumn::make('kursart')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('anrede'),
                TextColumn::make('vorname')->searchable(),
                TextColumn::make('nachname')->searchable(),
                TextColumn::make('telefonnr'),
                TextColumn::make('mitteilung')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('kommentar')
                    ->searchable()
                    ->limit(30)
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
