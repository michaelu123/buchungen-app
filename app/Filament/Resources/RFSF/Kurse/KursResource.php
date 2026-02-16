<?php

namespace App\Filament\Resources\RFSF\Kurse;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\RFSF\Kurs;
use App\Filament\Resources\RFSF\Kurse\Tables\KursTable;
use App\Filament\Resources\RFSF\Kurse\Schemas\KursForm;
use App\Filament\Resources\RFSF\Kurse\Pages\ListKurse;
use App\Filament\Resources\RFSF\Kurse\Pages\EditKurs;
use App\Filament\Resources\RFSF\Kurse\Pages\CreateKurs;

class KursResource extends Resource
{
    protected static ?string $model = Kurs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "RFSF-Kurse";
    protected static ?string $pluralModelLabel = 'RFSF-Kurse';
    protected static ?string $recordTitleAttribute = 'nummer';
    protected static ?string $slug = 'rfsfkurse';
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
