<?php

namespace App\Filament\Resources\Codier\Termine\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;

class TerminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notiz'),
                DatePicker::make('datum')
                    ->required()
                    ->disabledOn("edit"),
                TimePicker::make('beginn')
                    ->format('H:i')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('ende')
                    ->format('H:i')
                    ->seconds(false)
                    ->required(),
                Textarea::make('kommentar'),
            ]);
    }
}
