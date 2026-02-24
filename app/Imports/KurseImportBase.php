<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class KurseImportBase implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithMultipleSheets
{
    abstract protected function getKursModelClass(): string;
    abstract protected function getKursData($row, $note): array;

    public function sheets(): array
    {
        return [
            'Kurse' => new static,
        ];
    }

    public function onRow(Row $row): void
    {
        $rowData = $row->toArray(null, false, false);
        // Get the underlying PhpSpreadsheet Worksheet from the row delegate to access the cell and its comment
        // MUH: this works only because config/excel.php contains 'imports' => ['read_only' => false],
        /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
        $worksheet = $row->getDelegate()->getWorksheet();
        $comment = $worksheet->getComment([1, $row->getIndex()]);
        $note = $comment->getText()->getPlainText();
        $note = empty($note) ? null : $note;

        try {
            $kursData = $this->getKursData($rowData, $note);
            $modelClass = $this->getKursModelClass();
            if (
                $modelClass::where('nummer', $rowData['kursname'])
                    ->where('uhrzeit', $rowData['uhrzeit'])
                    ->first()
            ) {
                return;
            }
            (new $modelClass($kursData))->save();
        } catch (\Throwable $t) {
            Log::error("import failed:" . $t);
            return;
        }
    }

    public function fromExcelDateTime($dt)
    {
        $res = null;
        if (filled($dt)) {
            $res = Date::excelToDateTimeObject($dt);
        }
        return $res;
    }
}
