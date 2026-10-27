<?php

namespace App\Filament\Resources\RFSWartelisten\BasisDaten\Pages;

use App\Filament\Resources\RFSWartelisten\BasisDaten\BasisDatenResource;
use Filament\Resources\Pages\ListRecords;

class ListBasisDaten extends ListRecords
{
    protected static string $resource = BasisDatenResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
