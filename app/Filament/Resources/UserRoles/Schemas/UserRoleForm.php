<?php

namespace App\Filament\Resources\UserRoles\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $users = User::get()
            ->mapWithKeys(function (User $user): array {
                return [$user["id"] => $user->name];
            })
            ->all();
        return $schema->name("xxx")
            ->components([
                Select::make('user_id')
                    ->label("Benutzer")
                    ->options($users)
                    ->required(),
                Select::make('role')
                    ->label("Rolle")
                    ->options(["ADMIN" => "ADMIN", "TK" => "TK", "RFSA" => "RFSA", "RFSF" => "RFSF", "RFSFP" => "RFSFP", "RFS" => "RFS"])
                    ->required(),
            ]);
    }
}
