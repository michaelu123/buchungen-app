<?php

namespace App\Filament\Resources\KurseBase;

use Filament\Resources\Pages\EditRecord;

abstract class EditKurseBase extends EditRecord
{
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
}
