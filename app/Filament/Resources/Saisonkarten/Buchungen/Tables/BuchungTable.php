<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen\Tables;

use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Filament\Resources\KurseBase\KursTableActions;
use App\Models\Saisonkarten\BasisDaten;
use App\Models\Saisonkarten\Buchung;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class BuchungTable extends BuchungTableBase
{

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getBuchungenExportClass(): string
    {
        return "";
    }

    protected static function getBuchungenImportClass(): string
    {
        return "";
    }

    protected static function getKursModelClass(): string
    {
        return "";
    }


    public static function configure(Table $table): Table
    {
        $tableActions = new KursTableActions("", "", "", Buchung::class);
        $basisdaten = BasisDaten::first();
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingegangen am')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
                TextInputColumn::make('notiz')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('mitgliedsname')
                    ->searchable(),
                TextColumn::make('mitgliedsnummer')
                    ->numeric(thousandsSeparator: "")
                    ->sortable(),
                TextColumn::make('sknummer')
                    ->label("SK-Nummer")
                    ->numeric(thousandsSeparator: "")
                    ->sortable(),
                ...static::kontoFelder(Buchung::class),
                ...static::verifyFeld(Buchung::class),
                TextColumn::make('gesendet')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('sksenden')
                    ->label("SK senden")
                    ->disabled(
                        fn($record): bool => filled($record['notiz'])
                        || Buchung::$requireEmailVerification && !filled($record['verified'])
                        || !str_ends_with($record->email, "@adfc-muenchen.de") // TODO
                    )
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->action(function ($record): void {
                        $record->confirm();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                $tableActions->getEbicsAction($basisdaten)
            ]);
    }
}
