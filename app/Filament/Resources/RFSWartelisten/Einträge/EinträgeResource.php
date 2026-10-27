<?php

namespace App\Filament\Resources\RFSWartelisten\Einträge;

use App\Filament\Resources\RFSWartelisten\Einträge\Pages\EditEinträge;
use App\Filament\Resources\RFSWartelisten\Einträge\Pages\ListEinträge;
use App\Filament\Resources\RFSWartelisten\Einträge\Schemas\EinträgeForm;
use App\Filament\Resources\RFSWartelisten\Einträge\Tables\EinträgeTable;
use App\Models\RFSWartelisten\Warteliste;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EinträgeResource extends Resource
{
    protected static ?string $model = Warteliste::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'RFS-Wartelisten';
    protected static ?string $pluralModelLabel = 'Wartelisten-Einträge';
    protected static ?string $slug = 'wartelisteneinträge';
    protected static ?string $recordTitleAttribute = 'Wartelisten-Einträge';

    public static function form(Schema $schema): Schema
    {
        return EinträgeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EinträgeTable::configure($table);
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
            'index' => ListEinträge::route('/'),
            'edit' => EditEinträge::route('/{record}/edit'),
        ];
    }
}
