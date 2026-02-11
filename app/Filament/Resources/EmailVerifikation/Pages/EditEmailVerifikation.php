<?php

namespace App\Filament\Resources\EmailVerifikation\Pages;

use App\Filament\Resources\EmailVerifikation\EmailVerifikationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailVerifikation extends EditRecord
{
    protected static string $resource = EmailVerifikationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
