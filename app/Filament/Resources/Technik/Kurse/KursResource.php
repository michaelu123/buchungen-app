<?php

namespace App\Filament\Resources\Technik\Kurse;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\Technik\Kurs;
use App\Filament\Resources\Technik\Kurse\Tables\KursTable;
use App\Filament\Resources\Technik\Kurse\Schemas\KursForm;
use App\Filament\Resources\Technik\Kurse\Pages\ListKurse;
use App\Filament\Resources\Technik\Kurse\Pages\EditKurs;
use App\Filament\Resources\Technik\Kurse\Pages\CreateKurs;

class KursResource extends Resource
{
    protected static ?string $model = Kurs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "Technik-Kurse";
    protected static ?string $pluralModelLabel = 'Kurse';
    protected static ?string $recordTitleAttribute = 'titel';
    protected static ?string $slug = 'technikkurse';
    public static function form(Schema $schema): Schema
    {
        return KursForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KursTable::configure($table);
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
            'index' => ListKurse::route('/'),
            'create' => CreateKurs::route('/create'),
            'edit' => EditKurs::route('/{record}/edit'),
        ];
    }
}
