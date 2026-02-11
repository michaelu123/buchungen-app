<?php

namespace App\Filament\Resources\EmailVerifikation\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class EmailVerifikationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email Addresse')
                    ->email()
                    ->required(),
                DateTimePicker::make('verified')
                    ->label('Verifiziert am')
                ,
            ]);
    }
}
