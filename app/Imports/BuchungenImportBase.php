<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BuchungenImportBase implements OnEachRow, SkipsEmptyRows, WithHeadingRow, WithMultipleSheets
{
    public function __construct(protected string $kursModelClass = '', protected string $buchungModelClass = '')
    {
    }

    public function sheets(): array
    {
        return [
            'Buchungen' => new static,
        ];
    }

    protected function transformRow(array $rowData): array
    {
        $s = $rowData["strasse_und_hausnummer"];
        $parts = explode(" ", $s);
        $n = count($parts);
        if ($n === 1) {
            $strasse = $s;
            $hsnr = "";
        } else {
            $strasse = implode(" ", array_slice($parts, 0, $n - 1));
            $hsnr = $parts[$n - 1];
        }
        $rowData["strasse"] = $strasse;
        $rowData["hsnr"] = $hsnr;
        return $rowData;
    }

    private array $kursnummer2id = [];
    protected function kursIdFor(string $kursnummer): int|null
    {
        if (empty($this->kursnummer2id)) {
            $this->kursnummer2id = $this->kursModelClass::pluck("id", "nummer")->toArray();
        }
        return $this->kursnummer2id[$kursnummer] ?? null;
    }

    public function onRow(Row $row): void
    {
        $rowData = $row->toArray(null, false, false);
        $useTermin = str_contains($this->kursModelClass, "Termin");

        try {
            if (isset($rowData["email"])) {
                $buchungData = $rowData;
                $createdAt = $buchungData['created_at'] = $buchungData['zeitstempel'];
                $kurs = $this->kursModelClass::where("nummer", $buchungData['kursnummer'])->first();
                $buchungData["kurs_id"] = $kurs->id;
            } else {
                $rowData = $this->transformRow($rowData);
                // Get the underlying PhpSpreadsheet Worksheet from the row delegate to access the cell and its comment
                // MUH: this works only because config/excel.php contains 'imports' => ['read_only' => false],
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
                    'strasse' => $rowData['strasse'],
                    'hsnr' => $rowData['hsnr'],
                    'telefonnr' => $rowData['telefonnummer_fur_ruckfragen'],
                    'kontoinhaber' => $rowData['lastschrift_name_des_kontoinhabers'],
                    'iban' => $rowData['lastschrift_iban_kontonummer'],
                    'lastschriftok' => filled($rowData['zustimmung_zur_sepa_lastschrift']),
                    'verified' => $this->fromExcelDateTime($rowData['verifikation']),
                    'eingezogen' => $rowData['eingezogen'],
                    'betrag' => $rowData['zahlungsbetrag'],
                    'anmeldebestätigung' => $rowData['anmeldebestatigung'],
                    'kommentar' => $rowData['bemerkung'],
                ];
                if ($rowData["ermassigung"] ?? false) { // zur Zeit nur RFSA
                    $buchungData['ermäßigung'] = $rowData["ermassigung"];
                }
                if ($rowData["mitteilung"] ?? false) { // zur Zeit nur RFSA/F/FP
                    $buchungData['mitteilung'] = $rowData['mitteilung'];
                }
                $kurs_id = $this->kursIdFor($buchungData['kursnummer']);
                if ($kurs_id == null) {
                    return;
                }
                $buchungData["kurs_id"] = $this->kursIdFor($buchungData['kursnummer']);
            }

            $modelClass = $this->buchungModelClass;
            if ($useTermin) {
                if (
                    $modelClass::where('created_at', $createdAt)
                        ->where('email', $buchungData['email'])
                        ->where('datum', $buchungData['datum'])
                        ->first()
                ) {
                    return;
                }
                $kurs = $this->kursModelClass::where('datum', $buchungData['datum'])->where('beginn', $buchungData['beginn'])->first();
                $buchungData['termin_id'] = $kurs->id;
            } elseif (
                $modelClass::where('created_at', $createdAt)
                    ->where('email', $buchungData['email'])
                    ->where('kurs_id', $buchungData['kurs_id'])
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
