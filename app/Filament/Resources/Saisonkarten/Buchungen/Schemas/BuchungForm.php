<?php

namespace App\Filament\Resources\Saisonkarten\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\Saisonkarten\Buchung;
use Filament\Forms\Components\TextInput;

class BuchungForm extends BuchungFormBase
{

    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getKursModelClass(): string
    {
        return "";
    }

    protected static function personFelder(): array
    {
        return [
            TextInput::make('mitgliedsname')
                ->required(),
        ];
    }


    protected static function zusatzFelder(): array
    {
        return [
            TextInput::make('sknummer')
                ->label("SK-Nummer")
                ->numeric(),
        ];
    }
}
