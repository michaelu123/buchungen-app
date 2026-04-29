<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SammelImport implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithMultipleSheets
{
  protected float $sum = 0;
  protected int $cnt = 0;
  protected array $buchungenData = [];

  public function sheets(): array
  {
    return [
      'Buchungen' => $this,
    ];
  }

  public function onRow(Row $row): void
  {
    $rowData = $row->toArray(null, false, false);

    $betrag = $rowData['betrag'];
    $this->buchungenData[] = [
      "datum" => now()->format('Y-m-d'),
      "betrag" => $betrag,
      "zweck" => $rowData["zweck"],
      "iban" => $rowData['iban_kontonummer'],
      "kontoinhaber" => $rowData['name_des_kontoinhabers'],
    ];
    $this->sum += $betrag;
    $this->cnt++;
  }




  public function getList(): array
  {
    return [
      "sum" => $this->sum,
      "cnt" => $this->cnt,
      "buchungenData" => $this->buchungenData,
    ];
  }
  public function fromExcelDate($dt): string|null
  {
    $res = null;
    if (filled($dt)) {
      $res = Date::excelToDateTimeObject($dt)->format("Y/m/d");
    }
    return $res;
  }
}