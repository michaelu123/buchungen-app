<?php

namespace App\Filament\Resources\RFSA\Kurse\Tables;

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
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;
use App\Exports\RFSA\BuchungenExport;
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('uhrzeit'),
                TextColumn::make('kurstermine')
                    ->state(fn(Kurs $kurs) => $kurs->termine(true)),
                TextColumn::make('ersatztermine')
                    ->state(fn(Kurs $kurs) => $kurs->termine(false)),
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
                TextColumn::make('lehrer')->label("Lehrer:in")
                    ->searchable(),
                TextColumn::make('co_lehrer')->label("Co-Lehrer:in")
                    ->searchable(),
                TextColumn::make('co_lehrer2')->label("Co-Lehrer:in2")
                    ->searchable(),
                TextColumn::make('hospitant')->label("Hospitant:in")
                    ->searchable(),
                TextColumn::make('hospitant2')->label("Hospitant:in2")
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

