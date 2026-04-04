<?php

namespace App\Filament\Resources\BuchungenBase\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class BuchungTableBase
{
    abstract protected static function getBuchungModelClass(): string;

    abstract protected static function getBuchungenExportClass(): string;

    abstract protected static function getBuchungenImportClass(): string;

    abstract protected static function getKursModelClass(): string;

    protected static function nameColumn($useTermin): array
    {
        if ($useTermin) {
            return [
                TextColumn::make('termin.datum')
                    ->label('Datum')
                    ->date('D, d.m')
                    ->sortable(),
                TextColumn::make('uhrzeit')
                    ->label('Uhrzeit')
                    ->time('H:i')
                    ->sortable()
            ];
        } else {
            return [
                TextColumn::make('kursnummer')
                    ->label('Kursname')
                    ->searchable()
                    ->sortable()
            ];
        }
    }


    protected static function select($useTermin, $kursClass): Select
    {
        if ($useTermin) {
            $options = $kursClass::whereNull('notiz')
                ->pluck('datum', 'datum')
                ->mapWithKeys(function (\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $datum): array {
                    return [$datum => Carbon::parse($datum)->translatedFormat('D, d.m')];
                });
            return
                Select::make('termin.datum')
                    ->label('Filtern nach Datum')
                    ->placeholder('Wähle ein Datum')
                    ->options($options);
        } else {
            return
                Select::make('nummer')
                    ->label('Filtern nach Kurs')
                    ->placeholder('Wähle einen Kurs')
                    ->options($kursClass::whereNull('notiz')->pluck('nummer', 'nummer')->toArray());
        }
    }

    protected static function kontoFelder($buchungClass): array
    {
        if (!$buchungClass::$requireAbbuchung) {
            return [];
        }
        return [
            TextColumn::make('kontoinhaber')
                ->searchable(),
            TextColumn::make('iban'),
            IconColumn::make('lastschriftok')
                ->label('Lastschrift genehmigt')
                ->boolean(),
            TextColumn::make('eingezogen')
                ->datetime('d.m.Y H:i:s')
                ->sortable(),
            TextColumn::make('betrag')
                ->numeric(thousandsSeparator: "")
                ->sortable(),
        ];
    }

    protected static function verifyFeld($buchungClass): array
    {
        if (!$buchungClass::$requireEmailVerification) {
            return [];
        }
        return [
            TextColumn::make('verified')
                ->label('Email verifiziert')
                ->datetime('d.m.Y H:i:s')
                ->sortable(),
        ];
    }

    public static function zusatzFelder(): array
    {
        return [];
    }

    public static function zusatzAktionen(): array
    {
        return [];
    }

    public static function configure(Table $table): Table
    {
        $buchungClass = static::getBuchungModelClass();
        $exportClass = static::getBuchungenExportClass();
        $importClass = static::getBuchungenImportClass();
        $kursClass = static::getKursModelClass();
        $useTermin = str_contains($kursClass, 'Termin');

        return $table
            ->striped()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingegangen am')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
                TextInputColumn::make('notiz')
                    ->searchable()
                    ->sortable()
                    ->afterStateUpdated(fn() => $buchungClass::checkRestplätze()),
                ...static::nameColumn($useTermin),
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
                    ->numeric(thousandsSeparator: "")
                    ->sortable(),
                TextColumn::make('ort')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('strasse')
                    ->label('Straße')
                    ->searchable(),
                TextColumn::make('hsnr')
                    ->label('Hausnummer')
                    ->searchable(),
                TextColumn::make('mitgliedsnummer')
                    ->numeric(thousandsSeparator: "")
                    ->sortable(),
                TextColumn::make('telefonnr')
                    ->label('Telefon')
                    ->searchable(),
                ...static::kontoFelder($buchungClass),
                ...static::verifyFeld($buchungClass),
                ...static::zusatzFelder(),
                TextColumn::make('anmeldebestätigung')
                    ->label('Ab versendet')
                    ->datetime('d.m.Y H:i:s')
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
                        static::select($useTermin, $kursClass), // ->live(),
                        Select::make('notiz')
                            ->label('Filtern nach Notiz')
                            ->placeholder('Wähle mit oder ohne Notiz')
                            ->options(['leer' => 'ohne Notiz', 'nicht leer' => 'mit Notiz']), // ->live(),
                    ])
                    ->indicateUsing(
                        function (array $data): array {
                            $inds = [];
                            if (isset($data['termin']['datum'])) {
                                $inds[] = Indicator::make('Datum')->removeField('datum');
                            }
                            if (isset($data['nummer'])) {
                                $inds[] = Indicator::make('Kurs')->removeField('nummer');
                            }
                            if (isset($data['notiz'])) {
                                $inds[] = Indicator::make('Notiz')->removeField('notiz');
                            }

                            return $inds;
                        }
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        $datum = $data['termin']['datum'] ?? null;
                        $nummer = $data['nummer'] ?? null;
                        $notiz = $data['notiz'] ?? null;
                        return $query
                            ->when(
                                $datum,
                                fn($query) => $query->whereHas('termin', fn($q) => $q->where('datum', $datum))
                            )->when(
                                $nummer,
                                fn($query) => $query->where('kursnummer', $nummer)
                            )->when(
                                $notiz,
                                fn($query) => $notiz == 'leer' ?
                                $query->whereNull('notiz') :
                                $query->whereNotNull('notiz')
                            );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()->after(function (DeleteAction $action) use ($buchungClass): void {
                        $buchungClass::checkRestplätze();
                    }),
                    Action::make('Prüfen')
                        ->disabled(fn($record): bool => filled($record['notiz']))
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->action(function ($record): void {
                            $record->check();
                        }),
                    Action::make('Bestätigung senden')
                        ->disabled(
                            fn($record): bool => filled($record['notiz'])
                            || $buchungClass::$requireEmailVerification && !filled($record['verified'])
                            || filled($record['anmeldebestätigung'])
                            || !str_ends_with($record->email, "@adfc-muenchen.de") // TODO
                        )
                        ->icon(Heroicon::OutlinedEnvelope)
                        ->action(function ($record): void {
                            $record->confirm();
                        }),
                    ...static::zusatzAktionen(),
                ])->label('Aktionen')->button()->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->after(function (DeleteBulkAction $action) use ($buchungClass): void {
                        $buchungClass::checkRestplätze();
                    }),
                ]),
                Action::make('export')
                    ->Label('Excel Export')
                    ->tableIcon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function () use ($exportClass): BinaryFileResponse {
                        $ns = (new \ReflectionClass($exportClass))->getNamespaceName();
                        $parts = explode('\\', $ns);
                        $segment = end($parts);
                        return Excel::download(new $exportClass(null), $segment . "_" . 'Buchungen.xlsx');
                    }),
                Action::make('import')
                    ->label('Excel Import')
                    ->icon(Heroicon::OutlinedDocumentArrowUp)
                    ->schema([
                        FileUpload::make('xlsx')
                            ->label('Excel Datei auswählen')
                            ->required()
                            ->storeFiles(false)
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->mimeTypeMap([
                                '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ]),
                    ])
                    ->action(function (array $data) use ($importClass): \Maatwebsite\Excel\Excel {
                        /** @var TemporaryUploadedFile $tuf */
                        $tuf = $data['xlsx'];
                        $path = $tuf->getRealPath();
                        $excel = Excel::import(new $importClass, $path);
                        $res = $tuf->delete();
                        return $excel;
                    }),

            ]);
    }
}
