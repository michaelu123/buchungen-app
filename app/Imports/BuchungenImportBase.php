<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class BuchungenImportBase implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithMultipleSheets
{
    abstract protected function getBuchungModelClass(): string;

    public function sheets(): array
    {
        return [
            'Buchungen' => new static,
        ];
    }

    protected function transformRow(array $rowData)
    {
        return $rowData;
    }

    public function onRow(Row $row): void
    {
        $rowData = $row->toArray(null, false, false);

        try {
            if (isset($rowData["email"])) {
                $buchungData = $rowData;
                $createdAt = $buchungData['created_at'] = $buchungData['zeitstempel'];
            } else {
                $rowData = $this->transformRow($rowData);
                // Get the underlying PhpSpreadsheet Worksheet from the row delegate to access the cell and its comment
                // MUH: this works only because config/excel.php contains 'imports' => ['read_only' => false],
                /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
                $worksheet = $row->getDelegate()->getWorksheet();
                $comment = $worksheet->getComment([1, $row->getIndex()]);
                $note = $comment->getText()->getPlainText();
                $note = empty($note) ? null : $note;
                $createdAt = $this->fromExcelDateTime($rowData['zeitstempel']);
                $buchungData = [
                    'created_at' => $createdAt,
                    'notiz' => filled($note) ? $note : null,
                    'email' => $rowData['e_mail_adresse'],
                    'kursnummer' => $rowData['welchen_kurs_mochten_sie_belegen'],
                    'anrede' => $rowData['anrede'],
                    'vorname' => $rowData['vorname'],
                    'nachname' => $rowData['name'],
                    'postleitzahl' => (int) $rowData['postleitzahl'],
                    'ort' => $rowData['ort'],
                    'strasse_nr' => $rowData['strasse_und_hausnummer'],
                    'telefonnr' => $rowData['telefonnummer_fur_ruckfragen'],
                    'kontoinhaber' => $rowData['lastschrift_name_des_kontoinhabers'],
                    'iban' => $rowData['lastschrift_iban_kontonummer'],
                    'lastschriftok' => filled($rowData['zustimmung_zur_sepa_lastschrift']),
                    'verified' => $this->fromExcelDateTime($rowData['verifikation']),
                    'eingezogen' => $rowData['eingezogen'],
                    'betrag' => $rowData['zahlungsbetrag'],
                    'kommentar' => $rowData['kommentar'],
                ];
            }

            $modelClass = $this->getBuchungModelClass();
            if (
                $modelClass::where('created_at', $createdAt)
                    ->where('email', $buchungData['email'])
                    ->where('kursnummer', $buchungData['kursnummer'])
                    ->first()
            ) {
                return;
            }

            (new $modelClass($buchungData))->save();
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
