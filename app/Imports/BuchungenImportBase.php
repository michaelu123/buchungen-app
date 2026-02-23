<?php

namespace App\Imports;

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

    public function onRow(Row $row): void
    {
        $rowData = $row->toArray(null, false, false);
        // Get the underlying PhpSpreadsheet Worksheet from the row delegate to access the cell and its comment
        // MUH: this works only because config/excel.php contains 'imports' => ['read_only' => false],
        /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
        $worksheet = $row->getDelegate()->getWorksheet();
        $comment = $worksheet->getComment([1, $row->getIndex()]);
        $note = $comment->getText()->getPlainText();
        $createdAt = null;
        $verifiedAt = null;
        if (filled($rowData['zeitstempel'])) {
            $createdAt = Date::excelToDateTimeObject($rowData['zeitstempel']);
        }
        if (filled($rowData['verifikation'])) {
            $verifiedAt = Date::excelToDateTimeObject($rowData['verifikation']);
        }
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
            'verified' => $verifiedAt,
            'eingezogen' => $rowData['eingezogen'],
            'betrag' => $rowData['zahlungsbetrag'],
            'kommentar' => $rowData['kommentar'],
        ];

        $modelClass = $this->getBuchungModelClass();
        if (
            $modelClass::where('created_at', $createdAt)
                ->where('email', $rowData['e_mail_adresse'])
                ->where('kursnummer', $rowData['welchen_kurs_mochten_sie_belegen'])
                ->first()
        ) {
            return;
        }
        (new $modelClass($buchungData))->save();
    }
}
