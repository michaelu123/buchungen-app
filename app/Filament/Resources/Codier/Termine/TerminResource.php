<?php

namespace App\Filament\Resources\Codier\Termine;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\Codier\Termin;
use App\Filament\Resources\Codier\Termine\Tables\TerminTable;
use App\Filament\Resources\Codier\Termine\Schemas\TerminForm;
use App\Filament\Resources\Codier\Termine\Pages\ListTermine;
use App\Filament\Resources\Codier\Termine\Pages\EditTermin;
use App\Filament\Resources\Codier\Termine\Pages\CreateTermin;

class TerminResource extends Resource
{
    protected static ?string $model = Termin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "Codierungs-Termine";
    protected static ?string $pluralModelLabel = 'Codierungs-Termine';
    protected static ?string $recordTitleAttribute = 'datum';
    protected static ?string $slug = 'codiertermine';
    public static function form(Schema $schema): Schema
    {
        return TerminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TerminTable::configure($table);
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
            'index' => ListTermine::route('/'),
            'create' => CreateTermin::route('/create'),
            'edit' => EditTermin::route('/{record}/edit'),
        ];
    }
}
