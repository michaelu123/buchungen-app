<?php

namespace App\Filament\Resources\BuchungenBase\Pages;

use Filament\Resources\Pages\ListRecords;

abstract class ListBuchungenBase extends ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
