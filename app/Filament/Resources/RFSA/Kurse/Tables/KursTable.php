<?php

namespace App\Filament\Resources\RFSA\Kurse\Tables;

use App\Exports\RFSA\BuchungenExport;
use App\Exports\RFSA\KurseExport;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Filament\Resources\KurseBase\KursTableColumns;
use App\Imports\RFSA\KurseImport;
use App\Mail\RFSA\KurzVorher;
use App\Models\BaseBuchung;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class KursTable
{
    public static function configure(Table $table): Table
    {
        $kursTableActions = new KursTableActions(BuchungenExport::class, KurseExport::class, KurseImport::class, Buchung::class);
        $kursTableActions->addRecordAction(
            Action::make('Kurzvorher')
                ->icon(Heroicon::OutlinedEnvelope)
                ->action(function ($record): void {
                    $kurs = Kurs::find($record["id"]);
                    try {
                        Mail::to("asja.basko@adfc-muenchen.de")->send(new KurzVorher($kurs));
                    } catch (Throwable $t) {
                        Log::error("error " . $t->getMessage());
                        Log::error("error " . $t);
                    }
                    BaseBuchung::notifySuccess('Kurz vorher email versendet');
                })
        );

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
                KursTableColumns::getAbgebuchtColumn(),
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
                    ->sortable()
                    ->searchable(),
                TextColumn::make('co_lehrer')->label('Co-Lehrer:in')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('co_lehrer2')->label('Co-Lehrer:in2')
                    ->sortable()
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
                TextColumn::make('status')
                    ->sortable()
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
                [
                    ...$kursTableActions->getToolbarActions(),
                    Action::make('loadrvp')
                        ->label('RVP laden')
                        ->icon(Heroicon::OutlinedDocumentArrowUp)
                        ->action(fn() => Kurs::loadRvp("tag1", 13)),
                ]
            );
    }
}
