<?php

namespace App\Filament\Resources\Technik\Kurse\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Exports\Technik\BuchungenExport;
use App\Exports\Technik\KurseExport;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Imports\Technik\KurseImport;
use App\Models\Technik\Buchung;

class KursTable
{
    public static function configure(Table $table): Table
    {
        $kursTableActions = new KursTableActions(BuchungenExport::class, KurseExport::class, KurseImport::class, Buchung::class);

        return $table
            ->striped()
            ->columns([
                TextColumn::make('notiz')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nummer')
                    ->label('Nummer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titel')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('datum')
                    ->date('D, d.m')
                    ->sortable(),
                TextColumn::make('kursplätze')
                    ->numeric(),
                TextColumn::make('restplätze')
                    ->numeric(),
                TextColumn::make('leiter')->label("Leiter:in")
                    ->sortable(),
                TextColumn::make('leiter2')->label("Leiter:in2")
                    ->sortable(),
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
            ->recordActions(
                $kursTableActions->getRecordActions()
            )
            ->toolbarActions(
                $kursTableActions->getToolbarActions()
            );
    }
}
