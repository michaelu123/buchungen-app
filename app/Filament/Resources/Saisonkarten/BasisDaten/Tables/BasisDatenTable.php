<?php

namespace App\Filament\Resources\Saisonkarten\BasisDaten\Tables;

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
                TextColumn::make('jahr')
                    ->numeric(thousandsSeparator: ""),
                TextColumn::make('betrag')
                    ->numeric(thousandsSeparator: ""),
                IconColumn::make('offen')
                    ->label("Formular ist offen")
                    ->boolean(),
                TextColumn::make('sknummer')
                    ->label("Nächste SK-Nummer")
                    ->numeric(thousandsSeparator: ""),
                TextColumn::make('gueltigab')
                    ->label("Gültig ab"),
                TextColumn::make('gueltigbis')
                    ->label("Gültig bis"),
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
