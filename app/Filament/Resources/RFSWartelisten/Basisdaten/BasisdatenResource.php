<?php

namespace App\Filament\Resources\RFSWartelisten\BasisDaten;

use App\Filament\Resources\RFSWartelisten\BasisDaten\Pages\EditBasisDaten;
use App\Filament\Resources\RFSWartelisten\BasisDaten\Pages\ListBasisDaten;
use App\Filament\Resources\RFSWartelisten\BasisDaten\Schemas\BasisDatenForm;
use App\Filament\Resources\RFSWartelisten\BasisDaten\Tables\BasisDatenTable;
use App\Models\RFSWartelisten\BasisDaten;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BasisDatenResource extends Resource
{
    protected static ?string $model = BasisDaten::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RFS-Basisdaten';
    protected static string|UnitEnum|null $navigationGroup = 'RFS-Wartelisten';
    protected static ?string $pluralModelLabel = 'RFS-Basisdaten';
    protected static ?string $slug = 'rfsbasisdaten';

    public static function form(Schema $schema): Schema
    {
        return BasisDatenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BasisDatenTable::configure($table);
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
            'index' => ListBasisDaten::route('/'),
            'edit' => EditBasisDaten::route('/{record}/edit'),
        ];
    }
}
