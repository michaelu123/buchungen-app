<?php

namespace App\Filament\Resources\RFSA\Buchungen\Schemas;

use App\Filament\Resources\BuchungenBase\Schemas\BuchungFormBase;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;

class BuchungForm extends BuchungFormBase
{
    protected static function getBuchungModelClass(): string
    {
        return Buchung::class;
    }

    protected static function getKursModelClass(): string
    {
        return Kurs::class;
    }

    protected static function zusatzFelder(): array
    {
        return [
            TextInput::make('ermäßigung'),
        ];
    }
}
