<?php

namespace App\Filament\Resources\KurseBase;

use App\Models\BaseBuchung;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Saloon\XmlWrangler\Data\Element;
use Saloon\XmlWrangler\Data\RootElement;
use Saloon\XmlWrangler\XmlReader;
use Saloon\XmlWrangler\XmlWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class KursTableActions
{
    public function __construct(public string $exportClass, public string $importClass) {}

    public function getRecordActions(): array
    {
        return [
            ActionGroup::make([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('export')
                    ->label('Excel')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(function (Model $kurs): BinaryFileResponse {
                        return Excel::download(new $this->exportClass($kurs), $kurs->nummer.'.xlsx');
                    }),
                Action::make('ebics')
                    ->label('EBICS')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->requiresConfirmation()
                  // ->modalHeading("Ebics-Datei erstellen?")
                    ->modalDescription('Ebics-Datei erstellen?')
                    ->modalSubmitActionLabel('Ja, erstellen')
                    ->fillForm(fn (Model $kurs): array => $this->fillForm($kurs))
                    ->schema([
                        TextInput::make('eingezogen1')->label('Schon eingezogen:')->readonly()->inlineLabel(),
                        TextInput::make('eingezogen2')->label('Noch einzuziehen:')->readonly()->inlineLabel(),
                        TextInput::make('unverifiziert')->label('Nicht verifizierte Email:')->readonly()->inlineLabel(),
                        Toggle::make('einzug')->label('Einzug vermerken?')->default(false)->inlineLabel()->autofocus(),
                    ])
                    ->action(function (array $data, Model $kurs) {
                        return response()->streamDownload(function () use ($kurs, $data) {
                            echo $this->createEbics($kurs, $data['einzug']);
                        }, $kurs->nummer.'_ebics.xml', ['Content-type' => 'application/xml']);
                    }),

            ])->label('Aktionen')->button()->color('primary'),
            //
        ];
    }

    public function getToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
            Action::make('update')
                ->label('Update Restplätze')
                ->tableIcon(Heroicon::OutlinedArrowPath)
                ->action(function (): void {
                    BaseBuchung::checkRestPlätze();                      // do nothing, just redirect to the create page
                }),
            Action::make('import')
                ->label('Excel Import')
                ->icon(Heroicon::OutlinedDocumentArrowUp)
                ->schema([
                    FileUpload::make('xlsx')
                        ->label('Excel Datei auswählen')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->mimeTypeMap([
                            '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]),
                ])
                ->action(function ($data): \Maatwebsite\Excel\Excel {
                    return Excel::import(new $this->importClass, storage_path('app/private/'.$data['xlsx']));
                }),
        ];
    }

    protected function fillForm(Model $kurs): array
    {
        $schonEingezogen = 0;
        $nochZuEinziehen = 0;
        $unverifiziert = 0;

        foreach ($kurs->buchungen()->get() as $buchung) {
            // dd($buchung->notiz, !$buchung->lastschriftok, !$buchung->iban, !$buchung->verified, $buchung->eingezogen);
            if ($buchung->notiz || ! $buchung->lastschriftok || ! $buchung->iban) {
                continue;
            }
            if ($buchung->eingezogen) {
                $schonEingezogen++;
            } else {
                $nochZuEinziehen++;
            }
            if (! $buchung->verified) {
                $unverifiziert++;
            }
        }

        return [
            'eingezogen1' => $schonEingezogen,
            'eingezogen2' => $nochZuEinziehen,
            'unverifiziert' => $unverifiziert,
        ];
    }

    public function createEbics(Model $kurs, bool $einzug): string
    {
        // foreach ($kurs->buchungen()->get() as $buchung) {
        //   // dd($buchung->notiz, !$buchung->lastschriftok, !$buchung->iban, !$buchung->verified, $buchung->eingezogen);
        //   if ($buchung->notiz || !$buchung->lastschriftok || !$buchung->iban || !$buchung->verified || $buchung->eingezogen) {
        //     continue;
        //   }
        //   $cnt++;
        // }
        /*
        // return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
        //   . "<xxx>" . $kurs->nummer . ":  " . $cnt . " Einzug: " . ($einzug ? "Ja" : "Nein") . " Mandat " . $kurs->ebicsData()["mandat"] . "</xxx>";
        */
        $xmlString = $this->createXml($kurs);
        if ($einzug) {
            // TODO
        }

        return $xmlString;
    }

    protected $digits = '0123456789';

    protected $ascii_uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected $latin = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz':?,-(+.)/ ÄÖÜäöüß&*$%";

    protected function randomId(int $length): string
    {
        $r1 = $this->ascii_uppercase[random_int(0, \strlen($this->ascii_uppercase))];  // first a letter
        $r2 = '';
        for ($i = 0; $i < $length - 1; $i++) { // then any mixture of capital letters and numbers
            $r2 .= $this->charset[random_int(0, strlen($this->digits))];
        }

        return $r1.$r2;
    }

    protected function isLatin(string $s): bool
    {

        for ($i = 0; $i < \strlen($s); $i++) {
            if (strpos($this->latin, $s[$i]) == false) {
                return false;
            }
        }

        return true;
    }

    protected function convertToIsoDate(string $ts): string // 06.03.2022 17:28:38 -> 2022-03-06
    {
        if ($ts[2] == '.' and $ts[5] == '.') {
            return substr($ts, 6, 4) + '-' + substr($ts, 3, 2) + '-' + substr($ts, 0, 2);
        }
        if ($ts[4] == '-' && $ts[7] == '-') {
            return $ts;
        }

        return '1999-01-01'; // ??
    }

    protected function findElements(array $content, string $tagName): array
    {
        $results = [];
        foreach ($content as $key => $value) {
            $items = \is_array($value) ? $value : [$value];
            foreach ($items as $item) {
                if ($item instanceof Element) {
                    if ($key === $tagName) {
                        $results[] = $item;
                    }
                    $content = $item->getContent();
                    if (is_array($content)) {
                        $results = array_merge($results, $this->findElements($content, $tagName));
                    }
                }
            }
        }

        return $results;
    }

    protected function fillinIDs(array $rootContent): void
    {
        $msgIds = $this->findElements($rootContent, 'MsgId');
        foreach ($msgIds as $elem) {
            $elem->setContent('MSG'.$this->randomId(32));
        }

        $pmtInfIds = $this->findElements($rootContent, 'PmtInfId');
        foreach ($pmtInfIds as $elem) {
            $elem->setContent('PII'.$this->randomId(32));
        }
    }

    protected function fillinSumme(array $rootContent, float $summe, int $cnt): void
    {
        $ctrlSums = $this->findElements($rootContent, 'CtrlSum');
        foreach ($ctrlSums as $elem) {
            $elem->setContent(number_format($summe, 2));
        }

        $nbOfTxs = $this->findElements($rootContent, 'NbOfTxs');
        foreach ($nbOfTxs as $elem) {
            $elem->setContent((string) $cnt);
        }
    }

    protected function fillinDates(array $rootContent): void
    {
        $day1 = now();
        $creDtTms = $this->findElements($rootContent, 'CreDtTm');
        foreach ($creDtTms as $elem) {
            $elem->setContent($day1->format('Y-m-d\TH:i:s.v\Z'));

        }
        $day2 = $day1->addDays(2);
        $reqdColltnDt = $this->findElements($rootContent, 'ReqdColltnDt');
        foreach ($reqdColltnDt as $elem) {
            $elem->setContent($day2->format('Y-m-d'));

        }
    }

    /*
      def fillinDates(self):
          creDtTm = self.xmlt.getElementsByTagName("CreDtTm")
          now = datetime.datetime.utcnow()
          d = now.isoformat(timespec="milliseconds") + "Z"
          creDtTm[0].childNodes[0] = self.xmlt.createTextNode(d)

          reqdColltnDt = self.xmlt.getElementsByTagName("ReqdExctnDt" if self.sammel else "ReqdColltnDt")
          day2 = datetime.date.today() + datetime.timedelta(days=2)
          d = day2.isoformat()
          reqdColltnDt[0].childNodes[0] = self.xmlt.createTextNode(d)
    */

    protected XmlReader $xmlReader;

    protected XmlWriter $xmlWriter;

    // protected array $xmlt;
    protected function createXml(Model $kurs)
    {
        $this->xmlReader = XmlReader::fromString($this->xmlsAbbuchung);

        $elements = $this->xmlReader->elements();
        $document = $elements['Document'];
        $rootContent = $document->getContent();

        $this->fillinIDs($rootContent);
        $this->fillinSumme($rootContent, 100.0, 5);
        $this->fillinDates($rootContent);

        $this->xmlWriter = XmlWriter::make('UTF-8', '1.0', true);

        $root = new RootElement('Document');
        $root->setAttributes($document->getAttributes());
        // $root->setNamespaces($document->getNamespaces());

        $xmlString = $this->xmlWriter->write($root, $rootContent);

        return $xmlString;
    }

    private $xmlsAbbuchung = <<<'EOF'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02 pain.008.001.02.xsd">
    <CstmrDrctDbtInitn>
        <GrpHdr>
            <MsgId>MSG26022a8fb83cf1a515099ade7bdc3afc</MsgId>
            <CreDtTm>2019-03-27T11:25:44.620Z</CreDtTm>
            <NbOfTxs>1</NbOfTxs>
            <CtrlSum>0.01</CtrlSum>
            <InitgPty>
                <Nm>ALLG. DEUTSCHER FAHRRAD-CLUB KREISVERBAND MÜNCH. ADFC</Nm>
            </InitgPty>
        </GrpHdr>
        <PmtInf>
            <PmtInfId>PIIa671997ba9d14b0085f75f1353e9d008</PmtInfId>
            <PmtMtd>DD</PmtMtd>
            <NbOfTxs>1</NbOfTxs>
            <CtrlSum>0.01</CtrlSum>
            <PmtTpInf>
                <SvcLvl>
                    <Cd>SEPA</Cd>
                </SvcLvl>
                <LclInstrm>
                    <Cd>CORE</Cd>
                </LclInstrm>
                <SeqTp>OOFF</SeqTp>
            </PmtTpInf>
            <ReqdColltnDt>2019-03-29</ReqdColltnDt>
            <Cdtr>
                <Nm>ALLG. DEUTSCHER FAHRRAD-CLUB KREISVERBAND MÜNCH. ADFC</Nm>
            </Cdtr>
            <CdtrAcct>
                <Id>
                    <IBAN>DE62701500000904157781</IBAN>
                </Id>
            </CdtrAcct>
            <CdtrAgt>
                <FinInstnId>
                    <BIC>SSKMDEMMXXX</BIC>
                </FinInstnId>
            </CdtrAgt>
            <ChrgBr>SLEV</ChrgBr>
            <CdtrSchmeId>
                <Id>
                    <PrvtId>
                        <Othr>
                            <Id>DE44ZZZ00000793122</Id>
                            <SchmeNm>
                                <Prtry>SEPA</Prtry>
                            </SchmeNm>
                        </Othr>
                    </PrvtId>
                </Id>
            </CdtrSchmeId>
            <DrctDbtTxInf>
                <PmtId>
                    <EndToEndId>NOTPROVIDED</EndToEndId>
                </PmtId>
                <InstdAmt Ccy="EUR">0.01</InstdAmt>
                <DrctDbtTx>
                    <MndtRltdInf>
                        <MndtId>ADFC-M-RFS-2018</MndtId>
                        <DtOfSgntr>2019-03-27</DtOfSgntr>
                    </MndtRltdInf>
                </DrctDbtTx>
                <DbtrAgt>
                    <FinInstnId>
                        <Othr>
                            <Id>NOTPROVIDED</Id>
                        </Othr>
                    </FinInstnId>
                </DbtrAgt>
                <Dbtr>
                    <Nm>Vorname Nachname</Nm>
                </Dbtr>
                <DbtrAcct>
                    <Id>
                        <IBAN>DE12341234123412341234</IBAN>
                    </Id>
                </DbtrAcct>
                <RmtInf>
                    <Ustrd>Zweck</Ustrd>
                </RmtInf>
            </DrctDbtTxInf>
        </PmtInf>
    </CstmrDrctDbtInitn>
</Document>  
EOF;
}
