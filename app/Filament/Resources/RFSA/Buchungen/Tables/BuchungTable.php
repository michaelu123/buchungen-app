<?php

namespace App\Filament\Resources\RFSA\Buchungen\Tables;

use App\Exports\RFSA\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Imports\RFSA\BuchungenImport;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;

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
}
