<?php

namespace App\Filament\Resources\RFSA\Buchungen;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\RFSA\Buchung;
use App\Filament\Resources\RFSA\Buchungen\Tables\BuchungTable;
use App\Filament\Resources\RFSA\Buchungen\Schemas\BuchungForm;
use App\Filament\Resources\RFSA\Buchungen\Pages\ListBuchungen;
use App\Filament\Resources\RFSA\Buchungen\Pages\EditBuchung;
use App\Filament\Resources\RFSA\Buchungen\Pages\CreateBuchung;

class BuchungResource extends Resource
{
    protected static ?string $model = Buchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = "RFSA-Kurse";
    protected static ?string $pluralModelLabel = 'RFSA-Buchungen';
    protected static ?string $modelLabel = 'Buchung'; // ???
    protected static ?string $navigationLabel = 'RFSA-Buchungen'; // ???
    protected static ?string $slug = 'rfsabuchungen';

    protected static ?string $recordTitleAttribute = 'email';

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
            'create' => CreateBuchung::route('/create'),
            'edit' => EditBuchung::route('/{record}/edit'),
        ];
    }


}
