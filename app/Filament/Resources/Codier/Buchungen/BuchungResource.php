<?php

namespace App\Filament\Resources\Codier\Buchungen;

use App\Filament\Resources\BuchungenBase\BuchungResourceBase;
use App\Filament\Resources\Codier\Buchungen\Pages\CreateBuchung;
use App\Filament\Resources\Codier\Buchungen\Pages\EditBuchung;
use App\Filament\Resources\Codier\Buchungen\Pages\ListBuchungen;
use App\Filament\Resources\Codier\Buchungen\Schemas\BuchungForm;
use App\Filament\Resources\Codier\Buchungen\Tables\BuchungTable;
use App\Models\Codier\Buchung;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BuchungResource extends BuchungResourceBase
{
    protected static ?string $model = Buchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Codierungs-Termine';

    protected static ?string $pluralModelLabel = 'Codierungs-Buchungen';

    protected static ?string $modelLabel = 'Buchung';

    protected static ?string $navigationLabel = 'Codierungs-Buchungen';

    protected static ?string $slug = 'codierbuchungen';

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
