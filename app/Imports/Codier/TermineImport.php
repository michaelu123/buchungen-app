<?php

namespace App\Imports\Codier;

use App\Imports\KurseImportBase;
use App\Models\Codier\Termin;

class TermineImport extends KurseImportBase
{
    protected function getKursModelClass(): string
    {
        return Termin::class;
    }

    protected function getKursData($row, $note): array
    {
        return [];
    }
}
