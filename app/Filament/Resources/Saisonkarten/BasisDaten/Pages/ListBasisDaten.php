<?php

namespace App\Filament\Resources\Saisonkarten\BasisDaten\Pages;

use App\Filament\Resources\Saisonkarten\BasisDaten\BasisDatenResource;
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
