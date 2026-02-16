<?php

namespace App\Filament\Resources\RFSFP\Buchungen;

use App\Filament\Resources\BuchungenBase\BuchungResourceBase;
use App\Filament\Resources\RFSFP\Buchungen\Pages\CreateBuchung;
use App\Filament\Resources\RFSFP\Buchungen\Pages\EditBuchung;
use App\Filament\Resources\RFSFP\Buchungen\Pages\ListBuchungen;
use App\Filament\Resources\RFSFP\Buchungen\Schemas\BuchungForm;
use App\Filament\Resources\RFSFP\Buchungen\Tables\BuchungTable;
use App\Models\RFSFP\Buchung;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BuchungResource extends BuchungResourceBase
{
    protected static ?string $model = Buchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'RFSFP-Kurse';

    protected static ?string $pluralModelLabel = 'RFSFP-Buchungen';

    protected static ?string $modelLabel = 'Buchung';

    protected static ?string $navigationLabel = 'RFSFP-Buchungen';

    protected static ?string $slug = 'rfsfpbuchungen';

    protected static ?string $recordTitleAttribute = 'email';

    public static function getBuchungFormClass(): string
    {
        return BuchungForm::class;
    }

    public static function getBuchungTableClass(): string
    {
        return BuchungTable::class;
    }

    public static function getListBuchungenPageClass(): string
    {
        return ListBuchungen::class;
    }

    public static function getCreateBuchungPageClass(): string
    {
        return CreateBuchung::class;
    }

    public static function getEditBuchungPageClass(): string
    {
        return EditBuchung::class;
    }
}
