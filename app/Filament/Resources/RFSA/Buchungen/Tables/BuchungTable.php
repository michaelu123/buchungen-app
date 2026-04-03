<?php

namespace App\Filament\Resources\RFSA\Buchungen\Tables;

use App\Exports\RFSA\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Imports\RFSA\BuchungenImport;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class BuchungTable extends BuchungTableBase
{
    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getBuchungenExportClass(): string
    {
        return BuchungenExport::class;
    }

    protected static function getBuchungenImportClass(): string
    {
        return BuchungenImport::class;
    }

    protected static function getKursModelClass(): string
    {
        return Kurs::class;
    }

    public static function kontoFelder($buchungClass): array
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
            TextColumn::make('ermäßigung'),
            TextColumn::make('eingezogen')
                ->datetime('d.m.Y H:i:s')
                ->sortable(),
            TextColumn::make('betrag')
                ->numeric(thousandsSeparator: "")
                ->sortable(),
        ];
    }

}
