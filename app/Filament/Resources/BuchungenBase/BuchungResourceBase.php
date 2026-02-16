<?php

namespace App\Filament\Resources\BuchungenBase;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

abstract class BuchungResourceBase extends Resource
{
    protected static ?string $model = null; // Must be set in child class

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = ''; // Must be set in child class

    protected static ?string $pluralModelLabel = ''; // Must be set in child class

    protected static ?string $modelLabel = 'Buchung';

    protected static ?string $navigationLabel = ''; // Must be set in child class

    protected static ?string $slug = ''; // Must be set in child class

    protected static ?string $recordTitleAttribute = 'email';

    abstract public static function getBuchungFormClass(): string;

    abstract public static function getBuchungTableClass(): string;

    abstract public static function getListBuchungenPageClass(): string;

    abstract public static function getCreateBuchungPageClass(): string;

    abstract public static function getEditBuchungPageClass(): string;

    public static function form(Schema $schema): Schema
    {
        $formClass = static::getBuchungFormClass();

        return $formClass::configure($schema);
    }

    public static function table(Table $table): Table
    {
        $tableClass = static::getBuchungTableClass();

        return $tableClass::configure($table);
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
            'index' => static::getListBuchungenPageClass()::route('/'),
            'create' => static::getCreateBuchungPageClass()::route('/create'),
            'edit' => static::getEditBuchungPageClass()::route('/{record}/edit'),
        ];
    }
}
