<?php

namespace App\Filament\Resources\Codier\Buchungen\Tables;

use App\Exports\Codier\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Imports\Codier\BuchungenImport;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;
use Filament\Tables\Columns\TextInputColumn;

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
        return Termin::class;
    }

    protected static function zusatzFelder(): array
    {
        return [
            TextInputColumn::make('ein')
                ->label('EIN'),
        ];
    }

}
