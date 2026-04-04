<?php

namespace App\Filament\Resources\Codier\Buchungen\Tables;

use App\Exports\Codier\BuchungenExport;
use App\Filament\Resources\BuchungenBase\Tables\BuchungTableBase;
use App\Imports\Codier\BuchungenImport;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
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

    public static function zusatzFelder(): array
    {
        return [
            TextInputColumn::make('ein')
                ->label('EIN'),
        ];
    }

    public static function zusatzAktionen(): array
    {
        return [
            Action::make('retryEIN')
                ->label("EIN laden")
                ->disabled(fn($record): bool => filled($record['notiz']) || filled($record['ein']))
                ->icon(Heroicon::OutlinedCloudArrowDown)
                ->action(function ($record): void {
                    $record->retryEIN();
                }),
        ];
    }
}
