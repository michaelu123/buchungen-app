<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KurseExportBase implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
  use Exportable;

  /**
   * $kursClass is the fully-qualified class name for models
   */
  public function __construct(protected string $kursClass)
  {
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event): void {
        $event->sheet->getDelegate()->setTitle('Kurse');
      },
    ];
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->kursClass::all();
  }

  public function map($kurs): array // to be overridden
  {
    return [];
  }

  public function headings(): array // to be overridden
  {
    return [];
  }
}
