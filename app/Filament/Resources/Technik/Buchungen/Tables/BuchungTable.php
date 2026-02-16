<?php

namespace App\Filament\Resources\Technik\Buchungen\Tables;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use App\Models\Technik\Buchung;
use App\Exports\Technik\BuchungenExport;

class BuchungTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('created_at')
                    ->label("Eingegangen am")
                    ->dateTime("d.m.Y H:i:s")
                    ->sortable(),
                TextColumn::make('notiz'),
                TextColumn::make('kursnummer')
                    ->label('Kursname')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('anrede')
                    ->sortable(),
                TextColumn::make('vorname')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nachname')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('postleitzahl')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ort')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('strasse_nr')
                    ->label('Straße und Hausnummer')
                    ->searchable(),
                TextColumn::make('mitgliedsnummer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('telefonnr')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('kontoinhaber')
                    ->searchable(),
                TextColumn::make('iban'),
                IconColumn::make('lastschriftok')
                    ->label('Lastschrift genehmigt')
                    ->boolean(),
                TextColumn::make('verified')
                    ->label("Email verifiziert")
                    ->datetime()
                    ->sortable(),
                TextColumn::make('eingezogen')
                    ->datetime()
                    ->sortable(),
                TextColumn::make('betrag')
                    ->numeric()
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
                DeleteAction::make()->after(function (DeleteBulkAction $action) {
                    Buchung::checkRestplätze();
                }),
                Action::make("Prüfen")
                    ->tableIcon(Heroicon::OutlinedCheckCircle)
                    ->action(function (Buchung $record) {
                        $record->check();
                    }),
                Action::make("Bestätigung senden")
                    ->tableIcon(Heroicon::OutlinedEnvelope)
                    ->action(function (Buchung $record) {
                        $record->confirm();
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->after(function (DeleteBulkAction $action) {
                        Buchung::checkRestplätze();
                    }),
                ]),
                Action::make("export")
                    ->Label("Excel")
                    ->tableIcon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function (): BinaryFileResponse {
                        return Excel::download(new BuchungenExport(null), "Buchungen.xlsx");
                    }),

            ]);
    }
}
