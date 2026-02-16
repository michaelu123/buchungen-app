<?php

namespace App\Filament\Resources\RFSF\Buchungen;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\RFSF\Buchung;
use App\Filament\Resources\RFSF\Buchungen\Tables\BuchungTable;
use App\Filament\Resources\RFSF\Buchungen\Schemas\BuchungForm;
use App\Filament\Resources\RFSF\Buchungen\Pages\ListBuchungen;
use App\Filament\Resources\RFSF\Buchungen\Pages\EditBuchung;
use App\Filament\Resources\RFSF\Buchungen\Pages\CreateBuchung;

class BuchungResource extends Resource
{
    protected static ?string $model = Buchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = "RFSF-Kurse";
    protected static ?string $pluralModelLabel = 'RFSF-Buchungen';
    protected static ?string $modelLabel = 'Buchung'; // ???
    protected static ?string $navigationLabel = 'RFSF-Buchungen'; // ???
    protected static ?string $slug = 'rfsfbuchungen';

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
