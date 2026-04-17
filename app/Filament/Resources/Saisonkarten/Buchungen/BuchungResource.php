<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen;

use App\Filament\Resources\Saisonkarten\Buchungen\Pages\EditBuchung;
use App\Filament\Resources\Saisonkarten\Buchungen\Pages\ListBuchungen;
use App\Filament\Resources\Saisonkarten\Buchungen\Schemas\BuchungForm;
use App\Filament\Resources\Saisonkarten\Buchungen\Tables\BuchungTable;
use App\Models\Saisonkarten\Buchung;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BuchungResource extends Resource
{
    protected static ?string $model = Buchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Saisonkarten';
    protected static ?string $pluralModelLabel = 'SK-Buchungen';
    protected static ?string $slug = 'saisonkartenbuchungen';

    protected static ?string $recordTitleAttribute = 'mitgliedsname';

    public static function form(Schema $schema): Schema
    {
        return BuchungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuchungTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBuchungen::route('/'),
            'edit' => EditBuchung::route('/{record}/edit'),
        ];
    }
}
