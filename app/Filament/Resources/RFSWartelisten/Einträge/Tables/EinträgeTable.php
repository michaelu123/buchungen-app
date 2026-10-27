<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge\Tables;

use App\Exports\RFSWartelisten\WartelistenExport;
use App\Imports\RFSWartelisten\WartelistenImport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EinträgeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort("created_at", "desc")
            ->columns([
                TextColumn::make('created_at')
                    ->label("Eingangsdatum")
                    ->dateTime("d.m.Y H:i:s")
                    ->sortable(),
                TextColumn::make('kursart')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('anrede'),
                TextColumn::make('vorname')->searchable(),
                TextColumn::make('nachname')->searchable(),
                TextColumn::make('telefonnr'),
                TextColumn::make('mitteilung')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (\strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make('export')
                    ->Label('Excel Export')
                    ->tableIcon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function (): BinaryFileResponse {
                        return Excel::download(new WartelistenExport(), 'wartelisten.xlsx');
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
                    ->action(function (array $data): \Maatwebsite\Excel\Excel {
                        /** @var TemporaryUploadedFile $tuf */
                        $tuf = $data['xlsx'];
                        $path = $tuf->getRealPath();
                        $excel = Excel::import(new WartelistenImport(), $path);
                        $res = $tuf->delete();
                        return $excel;
                    }),
            ]);
    }
}
