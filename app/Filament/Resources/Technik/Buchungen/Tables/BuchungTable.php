<?php

namespace App\Filament\Resources\Technik\Buchungen\Tables;

use App\Exports\Technik\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Models\Technik\Buchung;
use App\Models\Technik\Kurs;

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

    protected static function getKursModelClass(): string
    {
        return Kurs::class;
    }
}
