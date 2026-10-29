<?php

namespace App\Mail\RFSA;

use App\Mail\BestaetigungBase;
use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class KurzVorher extends BestaetigungBase
{
    public $lehrer = [];
    public $startdatum = "";
    public $startdate = "";
    public $kursDetails_en = "";
    public function __construct(Kurs $kurs)
    {
        parent::__construct($kurs, new Buchung());
        if ($kurs["lehrer"]) {
            $this->lehrer[] = $kurs["lehrer"];
        }
        if ($kurs["co_lehrer"]) {
            $this->lehrer[] = $kurs["co_lehrer"];
        }
        if ($kurs["co_lehrer2"]) {
            $this->lehrer[] = $kurs["co_lehrer2"];
        }
        $this->startdatum = Carbon::parse($kurs["tag1"])->translatedFormat('l \d\e\n d.m.');
        $this->startdate = Carbon::parse($kurs["tag1"])->format('l, F d\t\h');
        $this->kursDetails_en = $kurs->kursDetails_en();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromAddress()),
            bcc: [new Address($this->fromAddress())],
            subject: 'Kurs ' . $this->kurs->nummer . ' beginnt bald!',
        );
    }

    protected function viewName(): string
    {
        return 'mail.rfsa.kurzvorher';
    }
}
