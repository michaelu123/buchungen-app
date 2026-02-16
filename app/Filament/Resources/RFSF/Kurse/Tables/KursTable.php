<?php

namespace App\Filament\Resources\RFSF\Kurse\Tables;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use App\Models\RFSF\Kurs;
use App\Models\RFSF\Buchung;
use App\Exports\RFSF\BuchungenExport;
use Carbon\Carbon;

class KursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('notiz'),
                TextColumn::make('nummer')
                    ->label('Nummer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('datum'),
                TextColumn::make('ersatztermin'),
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make("export")
                    ->Label("Excel")
                    ->tableIcon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function (Kurs $kurs): BinaryFileResponse {
                        return Excel::download(new BuchungenExport($kurs), $kurs->nummer . ".xlsx");
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make("update")
                    ->label("Update Restplätze")
                    ->tableIcon(Heroicon::OutlinedArrowPath)
                    ->action(function () {
                        Buchung::checkRestPlätze();                      // do nothing, just redirect to the create page
                    })
            ]);
    }
}

