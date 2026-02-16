<?php

namespace App\Filament\Resources\RFSFP\Buchungen\Tables;

use App\Exports\RFSFP\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Models\RFSFP\Buchung;

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
}
