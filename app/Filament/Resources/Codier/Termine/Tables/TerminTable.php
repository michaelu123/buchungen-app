<?php

namespace App\Filament\Resources\Codier\Termine\Tables;

use App\Exports\Codier\BuchungenExport;
use App\Exports\Codier\TermineExport;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Imports\Codier\TermineImport;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class TerminTable
{
    public static function configure(Table $table): Table
    {
        $terminTableActions = new KursTableActions(BuchungenExport::class, TermineExport::class, TermineImport::class, Buchung::class);

        return $table
            ->striped()
            ->columns([
                TextInputColumn::make('notiz')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('datum')
                    ->date('D, d.m')
                    ->sortable(),
                TextColumn::make('ort')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rvp')
                    ->label("URL")
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('beginn')
                    ->label('Beginn')
                    ->time('H:i')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ende')
                    ->label('Ende')
                    ->time('H:i')
                    ->searchable()
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
                $terminTableActions->getRecordActions()
            )
            ->toolbarActions(
                [
                    ...$terminTableActions->getToolbarActions(),
                    Action::make('loadrvp')
                        ->label('RVP laden')
                        ->icon(Heroicon::OutlinedDocumentArrowUp)
                        ->action(fn() => Termin::loadRvp()),
                    Action::make('importOld')
                        ->label('Import CSV')
                        ->icon(Heroicon::OutlinedDocumentArrowUp)
                        ->schema([
                            FileUpload::make('json')
                                ->label('JSON Datei auswählen')
                                ->required()
                                ->storeFiles(false)
                                ->acceptedFileTypes(['application/json']),
                        ])
                        ->action(function (array $data) {
                            /** @var TemporaryUploadedFile $tuf */
                            $tuf = $data['json'];
                            $path = $tuf->getRealPath();
                            Termin::importOldCodier($path);
                            $res = $tuf->delete();
                            return null;
                        }),

                ]
            );
    }
}
