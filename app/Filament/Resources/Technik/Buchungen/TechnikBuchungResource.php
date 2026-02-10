<?php

namespace App\Filament\Resources\Technik\Buchungen;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\TechnikBuchung;
use App\Filament\Resources\Technik\Buchungen\Tables\TechnikBuchungTable;
use App\Filament\Resources\Technik\Buchungen\Schemas\TechnikBuchungForm;
use App\Filament\Resources\Technik\Buchungen\Pages\ListTechnikBuchungen;
use App\Filament\Resources\Technik\Buchungen\Pages\EditTechnikBuchung;
use App\Filament\Resources\Technik\Buchungen\Pages\CreateTechnikBuchung;

class TechnikBuchungResource extends Resource
{
    protected static ?string $model = TechnikBuchung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = "Technik-Kurse";
    protected static ?string $pluralModelLabel = 'Buchungen';
    protected static ?string $modelLabel = 'Buchung'; // ???
    protected static ?string $navigationLabel = 'Buchungen'; // ???
    protected static ?string $slug = 'technikbuchungen';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return TechnikBuchungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TechnikBuchungTable::configure($table);
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
            'index' => ListTechnikBuchungen::route('/'),
            'create' => CreateTechnikBuchung::route('/create'),
            'edit' => EditTechnikBuchung::route('/{record}/edit'),
        ];
    }


}
