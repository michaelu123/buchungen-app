<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Support\Enums\Operation;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->autofocus()
                    ->required(),
                TextInput::make('email')
                    ->unique(ignoreRecord: true)
                    ->label('Email address')
                    ->email()
                    ->required(),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn(Operation::Edit)
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->hiddenOn(Operation::Edit)
                    ->required(),
            ]);
    }
}
