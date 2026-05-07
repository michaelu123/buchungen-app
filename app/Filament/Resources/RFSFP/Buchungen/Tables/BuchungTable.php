<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Tables;

use App\Exports\RFSFP\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Imports\RFSFP\BuchungenImport;
use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;
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

    public static function zusatzFelder(): array
    {
        return [
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
        ];
    }
}
