<?php

namespace App\Filament\Resources\EmailVerifikation\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\EmailVerifikation\EmailVerifikationResource;

class ListEmailVerifikations extends ListRecords
{
    protected static string $resource = EmailVerifikationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
