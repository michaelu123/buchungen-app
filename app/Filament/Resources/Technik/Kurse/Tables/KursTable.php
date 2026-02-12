<?php

namespace App\Filament\Resources\Technik\Kurse\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use App\Models\Technik\Buchung;

class KursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('notiz'),
                TextColumn::make('nummer')
                    ->label('Nummer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titel')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('datum')
                    ->date("d.m.Y")
                    ->sortable(),
                TextColumn::make('kursplätze')
                    ->numeric(),
                TextColumn::make('restplätze')
                    ->numeric(),
                TextColumn::make('leiter')
                    ->sortable(),
                TextColumn::make('leiter2')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime("d.m.Y H:i:s")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime("d.m.Y H:i:s")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
