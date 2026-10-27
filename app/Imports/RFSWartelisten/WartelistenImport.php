<?php

namespace App\Imports\RFSWartelisten;

use App\Models\RFSWartelisten\Warteliste;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WartelistenImport implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithMultipleSheets, SkipsUnknownSheets
{
    // für den Import von Google in den exportierten xlsx-Dateien die sheetnamen wie unten umbenennen und leere sheets löschen
    public function sheets(): array
    {
        return [
            'Wartelisten' => new static(),
            'Warteliste_RFSA' => new static,  // RFSA
            'Warteliste_RFSFG' => new static,  // RFSFG
            'Warteliste_RFSFA' => new static,  // RFSFA
            'Warteliste_RFSFS' => new static,  // RFSFS
            'Warteliste_RFSFP' => new static,  // RFSFP
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        // Log::info("Sheet {$sheetName} was skipped");
    }

    public function onRow(Row $row): void
    {
        $rowData = $row->toArray(null, false, false);
        try {
            if (isset($rowData["email"])) {
                $wlData = $rowData;
                $createdAt = $wlData['created_at'] = $wlData['zeitstempel'];
            } else {
                $sheetName = $row->getWorksheet()->getTitle();
                $kursart = match ($sheetName) {
                    // see resources\views\pages\rfs_wartelisten\⚡create.blade.php kursart
                    "Warteliste_RFSA" => "Anfängerkurse",
                    'Warteliste_RFSFG' => "Fahrsicherheitstraining-Grundkurse",  // RFSFG
                    'Warteliste_RFSFA' => "Fahrsicherheitstraining-Aufbaukurse",  // RFSFA
                    'Warteliste_RFSFS' => "Fahrsicherheitstraining-Senior:innenkurse",  // RFSFS
                    'Warteliste_RFSFP' => "Fahrpraxiskurse",
                    default => "???"
                };
                $createdAt = $this->fromExcelDateTime($rowData['zeitstempel']);
                $wlData = [
                    'created_at' => $createdAt,
                    'kursart' => $kursart,
                    'email' => $rowData['e_mail_adresse'],
                    'anrede' => $rowData['anrede'],
                    'vorname' => $rowData['vorname'],
                    'nachname' => $rowData['name'],
                    'telefonnr' => $rowData['telefonnummer_fur_ruckfragen'],
                ];
                if ($rowData["kommentar"] ?? false) {
                    $wlData['kommentar'] = $rowData["kommentar"];
                }
                if ($rowData["mitteilung"] ?? false) {
                    $wlData['mitteilung'] = $rowData['mitteilung'];
                }
            }
            if (
                Warteliste::where('created_at', $createdAt)
                    ->where('email', $wlData['email'])
                    ->first()
            ) {
                return;
            }
            if (!$wlData["telefonnr"]) {
                // db field is notnull, but some old data were null
                $wlData['telefonnr'] = "";
            }

            (new Warteliste($wlData))->save();
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

