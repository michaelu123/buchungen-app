<?php

namespace App\Filament\Resources\RFSF\Kurse\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Exports\RFSF\BuchungenExport;
use App\Exports\RFSF\KurseExport;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Imports\RFSF\KurseImport;
use App\Models\RFSF\Buchung;

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
                TextColumn::make('datum')
                    ->date('D, d.m'),
                TextColumn::make('ersatztermin')
                    ->date('D, d.m'),
                TextColumn::make('uhrzeit'),
                TextColumn::make('kursort'),
                TextColumn::make('kursplätze')
                    ->numeric(),
                TextColumn::make('restplätze')
                    ->numeric(),
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
                TextColumn::make('trainer')->label("Trainer:in")
                    ->searchable(),
                TextColumn::make('co_trainer')->label("Co-Trainer:in")
                    ->searchable(),
                TextColumn::make('hospitant')->label("Hospitant:in")
                    ->searchable(),
                TextColumn::make('liste_verschicken')
                    ->searchable(),
                TextColumn::make('abgesagt_am')
                    ->label("Abgesagt am")
                    ->searchable(),
                TextColumn::make('abgesagt_wg')
                    ->label("Abgesagt wegen")
                    ->searchable(),
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

