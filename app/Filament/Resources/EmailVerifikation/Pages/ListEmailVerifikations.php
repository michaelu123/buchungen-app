<?php

namespace App\Filament\Resources\EmailVerifikation\Pages;

use App\Filament\Resources\EmailVerifikation\EmailVerifikationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailVerifikation extends ListRecords
{
    protected static string $resource = EmailVerifikationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
