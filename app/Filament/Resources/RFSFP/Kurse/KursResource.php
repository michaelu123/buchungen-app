<?php

namespace App\Filament\Resources\RFSFP\Kurse;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\RFSFP\Kurs;
use App\Filament\Resources\RFSFP\Kurse\Tables\KursTable;
use App\Filament\Resources\RFSFP\Kurse\Schemas\KursForm;
use App\Filament\Resources\RFSFP\Kurse\Pages\ListKurse;
use App\Filament\Resources\RFSFP\Kurse\Pages\EditKurs;
use App\Filament\Resources\RFSFP\Kurse\Pages\CreateKurs;

class KursResource extends Resource
{
    protected static ?string $model = Kurs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "RFSFP-Kurse";
    protected static ?string $pluralModelLabel = 'RFSFP-Kurse';
    protected static ?string $recordTitleAttribute = 'nummer';
    protected static ?string $slug = 'rfsfpkurse';
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
