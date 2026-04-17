<?php

namespace App\Filament\Resources\Saisonkarten\BasisDaten;

use App\Filament\Resources\Saisonkarten\BasisDaten\Pages\EditBasisDaten;
use App\Filament\Resources\Saisonkarten\BasisDaten\Pages\ListBasisDaten;
use App\Filament\Resources\Saisonkarten\BasisDaten\Schemas\BasisDatenForm;
use App\Filament\Resources\Saisonkarten\BasisDaten\Tables\BasisDatenTable;
use App\Models\Saisonkarten\BasisDaten;
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
    protected static string|UnitEnum|null $navigationGroup = 'Saisonkarten';
    protected static ?string $pluralModelLabel = 'SK-Basisdaten';
    protected static ?string $slug = 'saisonkartenbasisdaten';


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
