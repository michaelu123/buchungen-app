<?php

namespace App\Filament\Resources\BuchungenBase\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class BuchungTableBase
{
    abstract protected static function getBuchungModelClass(): string;

    abstract protected static function getBuchungenExportClass(): string;

    abstract protected static function getKursModelClass(): string;

    public static function configure(Table $table): Table
    {
        $buchungClass = static::getBuchungModelClass();
        $exportClass = static::getBuchungenExportClass();
        $kursClass = static::getKursModelClass();

        return $table
            ->striped()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingegangen am')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
                TextColumn::make('notiz')
                    ->searchable()
                    ->sortable(),
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
                    ->label('Email verifiziert')
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
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('kurs-filter')
                    ->schema([
                        Select::make('nummer')
                            ->label('Filtern nach Kurs')
                            ->placeholder('Wähle einen Kurs')
                            ->options($kursClass::whereNull('notiz')->pluck('nummer', 'nummer')->toArray()), // ->live(),
                        Select::make('notiz')
                            ->label('Filtern nach Notiz')
                            ->placeholder('Wähle mit oder ohne Notiz')
                            ->options(['leer' => 'ohne Notiz', 'nicht leer' => 'mit Notiz']), // ->live(),
                    ])
                    ->indicateUsing(
                        function (array $data): array {
                            $inds = [];
                            if ($data['nummer']) {
                                $inds[] = Indicator::make('Kurs')->removeField('nummer');
                            }
                            if ($data['notiz']) {
                                $inds[] = Indicator::make('Notiz')->removeField('notiz');
                            }

                            return $inds;
                        }
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        $nummer = $data['nummer'];
                        $notiz = $data['notiz'];

                        return $query
                            ->when(
                                $nummer,
                                fn($query): Builder => $query->where('kursnummer', $nummer)
                            )->when(
                                $notiz,
                                fn($query): Builder => $notiz == 'leer' ?
                                $query->whereNull('notiz') :
                                $query->whereNotNull('notiz')
                            );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()->after(function (DeleteBulkAction $action) use ($buchungClass): void {
                        $buchungClass::checkRestplätze();
                    }),
                    Action::make('Prüfen')
                        ->disabled(fn($record) => filled($record['notiz']))
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->action(function ($record): void {
                            $record->check();
                        }),
                    Action::make('Bestätigung senden')
                        ->disabled(fn($record) => filled($record['notiz']))
                        ->icon(Heroicon::OutlinedEnvelope)
                        ->action(function ($record): void {
                            $record->confirm();
                        }),
                ])->label('Aktionen')->button()->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->after(function (DeleteBulkAction $action) use ($buchungClass): void {
                        $buchungClass::checkRestplätze();
                    }),
                ]),
                Action::make('export')
                    ->Label('Excel')
                    ->tableIcon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function () use ($exportClass): BinaryFileResponse {
                        return Excel::download(new $exportClass(null), 'Buchungen.xlsx');
                    }),

            ]);
    }
}
