<?php

namespace App\Filament\Resources\Technik\Kurse;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\TechnikKurs;
use App\Filament\Resources\Technik\Kurse\Tables\TechnikKursTable;
use App\Filament\Resources\Technik\Kurse\Schemas\TechnikKursForm;
use App\Filament\Resources\Technik\Kurse\Pages\ListTechnikKurse;
use App\Filament\Resources\Technik\Kurse\Pages\EditTechnikKurs;
use App\Filament\Resources\Technik\Kurse\Pages\CreateTechnikKurs;

class TechnikKursResource extends Resource
{
    protected static ?string $model = TechnikKurs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "Technik-Kurse";
    protected static ?string $pluralModelLabel = 'Kurse';
    protected static ?string $recordTitleAttribute = 'titel';
    protected static ?string $slug = 'technikkurse';
    public static function form(Schema $schema): Schema
    {
        return TechnikKursForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TechnikKursTable::configure($table);
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
            'index' => ListTechnikKurse::route('/'),
            'create' => CreateTechnikKurs::route('/create'),
            'edit' => EditTechnikKurs::route('/{record}/edit'),
        ];
    }
}
