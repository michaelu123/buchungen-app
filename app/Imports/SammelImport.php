<?php

namespace App\Imports;

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

  static $cache = [];

  protected $latin = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz':?,-(+.)/ ÄÖÜäöüß&*$%";

  protected function restrict(string $s): string
  {
    $r = static::$cache[$s] ?? null;
    if ($r != null) {
      return $r;
    }
    $r = $s;
    for ($i = 0; $i < \strlen($s); $i++) {
      if (strpos($this->latin, $s[$i]) == false) {
        // nice try?  
        switch ($s[$i]) {
          case 'À':
          case 'Á':
          case 'Â':
          case 'Ã':
          case 'Å':
          case 'Æ':
            $s[$i] = 'A';
            break;
          case 'à':
          case 'á':
          case 'â':
          case 'ã':
          case 'å':
          case 'æ':
            $s[$i] = 'a';
            break;
          case 'Ç':
            $s[$i] = 'C';
            break;
          case 'ç':
            $s[$i] = 'c';
            break;
          case 'Ð':
            $s[$i] = 'D';
            break;
          case 'ð':
            $s[$i] = 'd';
            break;
          case 'È':
          case 'É':
          case 'Ê':
          case 'Ë':
            $s[$i] = 'E';
            break;
          case 'è':
          case 'é':
          case 'ê':
          case 'ë':
            $s[$i] = 'e';
            break;
          case 'Ì':
          case 'Í':
          case 'Î':
          case 'Ï':
            $s[$i] = 'I';
            break;
          case 'ì':
          case 'í':
          case 'î':
          case 'ï':
            $s[$i] = 'i';
            break;
          case 'Ñ':
            $s[$i] = 'N';
            break;
          case 'ñ':
            $s[$i] = 'n';
            break;
          case 'Ò':
          case 'Ó':
          case 'Ô':
          case 'Õ':
          case 'Ø':
            $s[$i] = 'O';
            break;
          case 'ò':
          case 'ó':
          case 'ô':
          case 'õ':
          case 'ø':
            $s[$i] = 'o';
            break;
          case 'þ':
            $s[$i] = 'p';
            break;
          case 'Þ':
            $s[$i] = 'p';
            break;
          case 'Ù':
          case 'Ú':
          case 'Û':
            $s[$i] = 'U';
            break;
          case 'ù':
          case 'ú':
          case 'û':
            $s[$i] = 'u';
            break;
          case '×':
            $s[$i] = 'x';
            break;
          case 'Ý':
            $s[$i] = 'Y';
            break;
          case 'ý':
          case 'ÿ':
            $s[$i] = 'y';
            break;
          default:
            $s[$i] = 'X';
        }
      }
    }
    static::$cache[$r] = $s;
    return $s;
  }

  public function onRow(Row $row): void
  {
    $rowData = $row->toArray(null, false, false);

    $betrag = $rowData['betrag'];
    $this->buchungenData[] = [
      "datum" => now()->format('Y-m-d'),
      "betrag" => $betrag,
      "zweck" => $this->restrict($rowData["zweck"]),
      "iban" => $rowData['iban_kontonummer'],
      "kontoinhaber" => $this->restrict($rowData['name_des_kontoinhabers']),
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