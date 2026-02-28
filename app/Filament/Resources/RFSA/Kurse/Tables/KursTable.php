<?php

namespace App\Filament\Resources\RFSA\Kurse\Tables;

use App\Exports\RFSA\BuchungenExport;
use App\Exports\RFSA\KurseExport;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Imports\RFSA\KurseImport;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class KursTable
{
    public static function configure(Table $table): Table
    {
        $kursTableActions = new KursTableActions(BuchungenExport::class, KurseExport::class, KurseImport::class, Buchung::class);

        return $table
            ->striped()
            ->columns([
                TextInputColumn::make('notiz')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nummer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('uhrzeit'),
                TextColumn::make('kurstermine')
                    ->state(fn(Kurs $kurs): string => $kurs->termine(true)),
                TextColumn::make('ersatztermine')
                    ->state(fn(Kurs $kurs): string => $kurs->termine(false)),
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
                TextColumn::make('lehrer')->label('Lehrer:in')
                    ->searchable(),
                TextColumn::make('co_lehrer')->label('Co-Lehrer:in')
                    ->searchable(),
                TextColumn::make('co_lehrer2')->label('Co-Lehrer:in2')
                    ->searchable(),
                TextColumn::make('hospitant')->label('Hospitant:in')
                    ->searchable(),
                TextColumn::make('hospitant2')->label('Hospitant:in2')
                    ->searchable(),
                TextColumn::make('liste_verschicken')
                    ->searchable(),
                TextColumn::make('abgesagt_am')
                    ->label('Abgesagt am')
                    ->searchable(),
                TextColumn::make('abgesagt_wg')
                    ->label('Abgesagt wegen')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d.m.Y H:i:s')
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
