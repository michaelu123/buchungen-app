<?php

namespace App\Filament\Resources\Saisonkarten\BasisDaten\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BasisDatenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('jahr')
                    ->required()
                    ->numeric(),
                TextInput::make('betrag')
                    ->required()
                    ->numeric(),
                Toggle::make('offen')
                    ->label("Formular ist offen")
                    ->required(),
                TextInput::make('sknummer')
                    ->label("Nächste SK-Nummer")
                    ->required()
                    ->numeric(),
                TextInput::make('gueltigab')
                    ->label("Gültig ab")
                    ->required(),
                TextInput::make('gueltigbis')
                    ->label("Gültig bis")
                    ->required(),
            ]);
    }
}
