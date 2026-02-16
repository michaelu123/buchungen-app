<?php

use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Checkbox;
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;

new class extends Component implements HasSchemas {
    use InteractsWithSchemas;
    protected static ?string $model = Buchung::class;
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $kurse = Kurs::whereNull("notiz")
            ->where("restplätze", ">", 0)
            ->get()
            ->mapWithKeys(function (Kurs $kurs) {
                return [$kurs["nummer"] => $kurs->kursDetails() . ", freie Plätze: " . $kurs["restplätze"]];
            })->all();
        return $schema
            ->components([
                TextInput::make('email')
                    ->belowLabel("Die Bestätigung der Buchung erfolgt per E-Mail. Bitte geben Sie eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->belowLabel("Falls Sie ADFC-Mitglied sind, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                    ->rules("digits:8"),
                Radio::make("kursnummer")
                    ->label("Kurs")
                    ->belowLabel("Ich möchte mich für folgenden Kurs anmelden:")
                    ->options(
                        $kurse,
                    )
                    ->required(),
                Select::make('anrede')
                    ->options(["Herr" => "Herr", "Frau" => "Frau", "" => "Keine Angabe"]),
                TextInput::make('vorname')
                    ->required(),
                TextInput::make('nachname')
                    ->required(),
                TextInput::make('postleitzahl')
                    ->required()
                    ->numeric(),
                TextInput::make('ort')
                    ->required(),
                TextInput::make('strasse_nr')
                    ->label('Straße und Hausnummer')
                    ->required(),
                TextInput::make('telefonnr')
                    ->belowLabel("Bitte geben Sie eine Telefonnummer an, unter der wir Sie erreichen können, falls es Rückfragen zu Ihrer Anmeldung gibt.")
                    ->label('Telefon')
                    ->tel()
                    ->required(),
                TextInput::make('kontoinhaber')
                    ->belowLabel("Bitte geben Sie den Namen des Kontoinhabers an, von dem die Lastschrift erfolgen soll.")
                    ->required(),
                TextInput::make('iban')
                    ->belowLabel("Bitte geben Sie die IBAN des Kontos an, von dem die Lastschrift erfolgen soll.")
                    ->rules([
                        fn(): Closure => function ($attribute, $value, Closure $fail) {
                            if (!Buchung::test_iban($value)) {
                                $fail('Die IBAN ist ungültig.');
                            }
                        },
                    ])
                    ->required(),
                Checkbox::make('lastschriftok')
                    ->belowContent(new HtmlString(<<<EOD
Hiermit ermächtige ich den ADFC München e.V. (Gläubiger-Identifikationsnummer: DE44ZZZ00000793122), den Teilnahmebeitrag 
aus dieser Anmeldung von meinem o.a. Bankkonto bei Fälligkeit unter Angabe der Mandatsreferenz „ADFC-M-TK“ einzuziehen. 
Zugleich weise ich mein Kreditinstitut an, vom ADFC München e.V. auf mein Konto gezogene SEPA-Lastschrift einzulösen. 
Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. 
Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.

<br><br><strong>Anmeldebedingungen:</strong><br>

Für alle Kurse ist eine Anmeldung erforderlich (online).
<p>
- Die Annahme der Kursanmeldung durch den ADFC München e.V. ist erfolgt, wenn sie durch eine Bestätigungsmail schriftlich quittiert wird.
</p><p>
- Die Bezahlung erfolgt per Bankeinzug. Die Kursgebühr ist mit Zustandekommen des Vertrags fällig. Die Teilnahme am Kurs ist erst gesichert 
mit Eingang der Lastschrift auf dem Bankkonto des ADFC München e.V.
</p><p>
- Sie können nur durch schriftliche Erklärung (Email an den Absender der Bestätigungsmail) von einem noch nicht begonnenen Kurs zurücktreten. 
Erfolgt der Rücktritt bis 72 Stunden vor Kursbeginn, erstatten wir die Kursgebühr zurück.
</p><p>
- Mit der Anmeldung stimmen Sie unseren Anmeldebedingungen und unserem Hygienekonzept zu und machen sich damit vertraut.
</p>

<br><strong>Datenschutzerklärung gemäß Art. 13 Datenschutz-Grundverordnung (DSGVO):</strong><br>

Der „Allgemeine Deutsche Fahrrad-Club München e.V.“ speichert die Anmeldedaten nur zur Erfüllung des vereinbarten Schulungsverhältnisses. 
Verantwortliche Stelle ist der Verein „Allgemeiner Deutscher Fahrrad-Club München e.V., Platenstraße 4, 80336 München. 
Die in diesem Anmeldeformular erhobenen personenbezogenen Daten Name, Vorname, Postanschrift, E-Mail-Adresse, diverse Telefonnummern 
und die Bankverbindung (bei einer SEPA-Lastschriftvereinbarung) werden ausschließlich zum Zwecke der Verwaltung und Betreuung der 
angemeldeten Kursteilnehmer/-innen genutzt.

Die Daten werden an Dritte nur bei Vorliegen einer rechtlichen Verpflichtung übermittelt. Die Nutzung zu Werbezwecken findet nicht statt.
Die personenbezogenen Daten werden nach Abschluss des Kurses gelöscht, soweit sie nicht entsprechend rechtlicher Vorgaben länger 
aufbewahrt werden müssen (SEPA-Lastschriftverfahren).
Jede Kursteilnehmerin bzw. jeder Kursteilnehmer hat im Rahmen der gesetzlichen Vorgaben das Recht auf Auskunft über die personenbezogenen 
Daten, die zu seiner Person bei der hier genannten verantwortlichen Stelle gespeichert sind, sowie das Recht auf Korrektur fehlerhafter 
Daten und ein Beschwerderecht beim Bayerischen Landesamt für Datenschutzaufsicht. (Eine ausführliche Erklärung des Vereins 
unter: https://www.adfc-muenchen.de/datenschutzerklaerung/)
EOD))
                    ->label('Lastschrift genehmigt')
                    ->default(true)
                    ->accepted()
                    ->required(),
            ])->statePath('data');
    }

    public function create(): void
    {
        Buchung::createBuchung($this->form->getState());
        redirect()->route('buchung.ok')->with('msg', "Sie erhalten in Kürze eine E-Mail.");
    }
}
?>

<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        <div class="flex flex-row justify-between items-center">
            <p class="lg:text-5xl text-2xl">Anmeldung zu einem Kurs der Radfahrschule</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Mit diesem Formular können Sie sich für einen Kurs der Radfahrschule des ADFC München anmelden. Wir brauchen
            Ihre
            Email-Adresse für die Bestätigungs-Mails. Die Kursgebühr von 120€ wird per Lastschrift eingezogen. Halten
            Sie dafür
            bitte Ihre IBAN-Kontonummer bereit. Sie finden die Kurse auch in
            unserem
            <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank"
                href="https://touren-termine.adfc.de/suche?fromNow=true&eventType=Termin&includedTags=11&latLng=48.1351253%2C11.5819806&place=M%C3%BCnchen">
                Termin-Portal
            </a>.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Anmelden
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament::section>