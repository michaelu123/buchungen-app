<?php

namespace App\Filament\Resources\EmailVerifikation;

use UnitEnum;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use BackedEnum;
use App\Models\EmailVerifikation;
use App\Filament\Resources\EmailVerifikation\Tables\EmailVerifikationTable;
use App\Filament\Resources\EmailVerifikation\Schemas\EmailVerifikationForm;
use App\Filament\Resources\EmailVerifikation\Pages\ListEmailVerifikation;
use App\Filament\Resources\EmailVerifikation\Pages\EditEmailVerifikation;
use App\Filament\Resources\EmailVerifikation\Pages\CreateEmailVerifikation;

class EmailVerifikationResource extends Resource
{
    protected static ?string $model = EmailVerifikation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'email';
    protected static string|UnitEnum|null $navigationGroup = "Allgemein";

    public static function form(Schema $schema): Schema
    {
        return EmailVerifikationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailVerifikationTable::configure($table);
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
            'index' => ListEmailVerifikation::route('/'),
            'create' => CreateEmailVerifikation::route('/create'),
            'edit' => EditEmailVerifikation::route('/{record}/edit'),
        ];
    }
}
