<?php

use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Checkbox;
use App\Models\RFSA\Kurs;
use App\Models\RFSA\Buchung;

new class extends Component implements HasSchemas {
    // noinspection PhpUnusedAliasInspection
    /** @use \Filament\Schemas\Concerns\InteractsWithSchemas */
    use \Filament\Schemas\Concerns\InteractsWithSchemas;
    protected const _TRAITS = [\Filament\Schemas\Concerns\InteractsWithSchemas::class];

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
            ->mapWithKeys(function (Kurs $kurs): array {
                return [$kurs->nummer => $kurs->kursDetails() . ", freie Plätze: " . $kurs->restplätze];
            })->all();
        return $schema
            ->components([
                TextInput::make('email')
                    ->belowLabel("Die Bestätigung der Buchung erfolgt per E-Mail. Bitte geben Sie eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                // TextInput::make('mitgliedsnummer')
                //     ->belowLabel("Falls Sie ADFC-Mitglied sind, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                //     ->rules("digits:8"),
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
                        fn(): \Closure => function ($attribute, $value, \Closure $fail): void {
                            if (!Buchung::test_iban($value)) {
                                $fail('Die IBAN ist ungültig.');
                            }
                        },
                    ])
                    ->required(),
                Checkbox::make('lastschriftok')
                    ->belowContent(new HtmlString(<<<EOD
Hiermit ermächtige ich den ADFC München e.V. (Gläubiger-Identifikationsnummer: DE44ZZZ00000793122), den Teilnahmebeitrag 
aus dieser Anmeldung von meinem o.a. Bankkonto bei Fälligkeit unter Angabe der Mandatsreferenz „ADFC-M-RFS“ einzuziehen. 
Zugleich weise ich mein Kreditinstitut an, vom ADFC München e.V. auf mein Konto gezogene SEPA-Lastschrift einzulösen. 
Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. 
Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.

<br><br><strong>Teilnahmebedingungen und Datenschutzerklärung:</strong><br>

Die Teilnahmebedingungen für die Kurse der Radfahrschule regeln Dinge wie Anmeldung, Bezahlung und Rücktritt und 
gelten sowohl für Anfängerkurse wie auch für FahrSicherheitsTrainings aller Stufen.<br><br>

1. Präambel:<br>
Die vom ADFC München e.V. durchgeführten Radfahrschulungen für Fahranfänger:innen und FahrSicherheitsTrainings 
fortgeschrittener Radfahrer:innen dienen entsprechend dem Vereinsziel der Förderung des Fahrradfahrens. 
Sie dienen keinem gewerblichen oder kommerziellen Zweck. Die Radfahrschulungen werden von ehrenamtlichen 
Mitgliedern geplant, organisiert und durchgeführt. Die Bedingungen sind für ADFC-Mitglieder und Nichtmitglieder 
gleich, soweit nicht anders angegeben.<br><br>

2. Anmeldung:<br>
Mit der Anmeldung (Online) bietet der/die Teilnehmer:in dem ADFC München e.V. den Abschluss eines der Anmeldung 
entsprechenden Schulungsvertrages an. Die Annahme der Kursanmeldung durch den ADFC München e.V. ist erfolgt, 
wenn sie durch die Radfahrschule des ADFC München e.V. schriftlich/per Mail bestätigt wird.<br><br>

3. Bezahlung:<br>
Die Bezahlung des Teilnahmebetrages kann ausschließlich im Wege des SEPA-Lastschriftverfahrens erfolgen. 
Der Teilnahmebetrag ist mit Zustandekommen des Vertrags fällig und wird innerhalb von 2 Wochen nach Anmeldebestätigung 
eingezogen. Die Teilnahme am Kurs ist erst mit Eingang des Lastschriftbetrages auf dem Bankkonto des ADFC München e.V. gesichert.<br><br>

4. Rücktritt von einem Schulungskurs / Absage des Kurses seitens des ADFC München e.V.<br>
Ein:e Teilnehmer:in kann nur durch schriftliche Erklärung von einem noch nicht begonnenen Schulungskurs zurücktreten. 
Erfolgt der Rücktritt bis 7 Tage vor Beginn der Ausbildung, erstatten wir den Teilnahmebetrag abzüglich eines 
Bearbeitungsentgelts zurück. Bei mehrtägigen Schulungen beträgt dieser 15 €, bei eintägigen Schulungen 50% des 
Teilnahmebetrages. Bei einem späteren Rücktritt oder Nichterscheinen zum Kurs erfolgt keine Erstattung. 
Sollte der Kurs seitens des ADFC München e.V. abgesagt werden müssen, wird der Teilnahmebetrag unverzüglich 
nach Absage in voller Höhe erstattet.<br><br>

5. Risiken:<br>
Da die Teilnahme an einer Radfahrschulung bzw. einem FahrSicherheitsTraining für Ungeübte anstrengend ist, 
sollte eine ausreichende körperliche Fitness vorhanden sein. Wenn Sie sich nicht sicher sind, die Schulungsdauer 
von jeweils 90 bzw. bis zu 240 Minuten pro Termin (je nach gewähltem Kurs) ohne Erschöpfung überstehen zu können, 
lassen Sie sich bitte ärztlich beraten. Bei den Anfängerkursen werden Schulungsräder zur Verfügung gestellt. 
Diese Fahrräder sind bis 100 kg Körpergewicht zugelassen. Für eine Teilnahme an einem Fortgeschrittenenkurs werden 
Grundkenntnisse im Fahrradfahren vorausgesetzt.
Während des Kurses sind die Regeln der Straßenverkehrsordnung zu beachten. Der/die Teilnehnmer:in ist sich bewusst, 
dass die Sorgfalt des ADFC München e.V. bei der Erfüllung von Verkehrssicherungspflichten hinsichtlich 
der Sicherheit des Trainingsgeländes sich billigerweise nur auf vorhersehbare Risiken erstrecken kann. 
Sie/Er akzeptiert daher, dass der ADFC nicht verpflichtet ist, Maßnahmen zu ergreifen, die nicht mehr in 
einem angemessenen Verhältnis zu der Wahrscheinlichkeit und dem Ausmaß eines etwaigen Schadens stehen. 
Dabei ist entscheidend die pflichtgemäße Betrachtung des ADFC München e.V. vor Beginn der jew. Schulungsveranstaltung.<br><br>

6. Haftungsbeschränkung:<br>
Die Teilnahme erfolgt auf eigenes Risiko! Der/die Teilnehmer:in erklärt, wie schon unter Ziffer 5 erwähnt, dass sein/ihr 
Gesundheitszustand den Anforderungen der Radfahrschulung entspricht. Schadenersatzansprüche gegen den 
ADFC München e.V., gleich aus welchem Rechtsgrunde, bestehen nur, soweit dem ADFC München e.V. Vorsatz 
oder grobe Fahrlässigkeit zur Last fallen. Von dem Haftungsausschluss ausgenommen sind Schadensersatzansprüche 
aufgrund einer Verletzung des Lebens, des Körpers, der Gesundheit und Schadensersatzansprüche aus der Verletzung 
wesentlicher Vertragspflichten durch den ADFC München e.V. Wesentliche Vertragspflichten sind solche, deren Erfüllung 
zur Erreichung des Vertragsziels notwendig ist. Für diese Schäden haftet der ADFC München e.V. bei Schäden, 
die nicht Personenschäden sind, und die nicht auf Vorsatz oder grober Fahrlässigkeit beruhen, mit maximal 4.000,00 € 
je Schulung und Teilnehmer; bei Personenschäden mit maximal 75.000,00 €. Der vorstehende Haftungsausschluss 
gilt auch zugunsten der gesetzlichen Vertreter und Erfüllungsgehilfen des ADFC München e.V., sofern der/die Teilnehmer:in 
Ansprüche gegen diese geltend macht.<br><br>

7. Datenschutzerklärung gemäß Art. 13 Datenschutz-Grundverordnung (DSGVO):<br>
Die Radfahrschule des Vereins „Allgemeiner Deutscher Fahrrad-Club München e.V.“ speichert die Anmeldedaten nur zur 
Erfüllung des vereinbarten Schulungsverhältnisses. Verantwortliche Stelle ist der Verein „Allgemeiner Deutscher 
Fahrrad-Club München e.V., Platenstraße 4, 80336 München. Die in diesem Anmeldeformular erhobenen personenbezogenen 
Daten Name, Vorname, Postanschrift, E-Mail-Adresse, diverse Telefonnummern und die Bankverbindung 
(bei einer SEPA-Lastschriftvereinbarung) werden ausschließlich zum Zwecke der Verwaltung und Betreuung der angemeldeten 
Kursteilnehmer/-innen genutzt.
Die Daten werden an Dritte nur bei Vorliegen einer rechtlichen Verpflichtung übermittelt. Die Nutzung zu Werbezwecken 
findet nicht statt.<br>
Die personenbezogenen Daten werden nach Abschluss des Kurses gelöscht, soweit sie nicht entsprechend rechtlicher 
Vorgaben länger aufbewahrt werden müssen (SEPA-Lastschriftverfahren).
Jede Kursteilnehmerin bzw. jeder Kursteilnehmer hat im Rahmen der gesetzlichen Vorgaben das Recht auf Auskunft über 
die personenbezogenen Daten, die zu seiner Person bei der hier genannten verantwortlichen Stelle gespeichert sind, s
owie das Recht auf Korrektur fehlerhafter Daten und ein Beschwerderecht beim Bayerischen Landesamt für Datenschutzaufsicht. 
(Eine ausführliche Erklärung des Vereins unter:<br>
https://muenchen.adfc.de/datenschutz)
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
            <p class="lg:text-5xl text-2xl">Anmeldung zu einem Kurs für Anfänger:innen der Radfahrschule München</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Sie können sich für einen der unten genannten Kurse anmelden.<br><br>

            Die Teilnahmegebühr für den Kurs (16 Unterrichtseinheiten) beträgt € 120,00.<br><br>

            Die Bezahlung erfolgt durch Lastschrift, bitte halten Sie dafür die IBAN-Kontonummer bereit. Wir belasten
            Ihr Konto erst, nachdem wir die Anmeldung per E-Mail bestätigt haben.<br><br>

            Durch Teilnehmende nicht wahrgenommene Stunden können nicht nachgeholt werden, da die Kursinhalte
            aufeinander aufbauen.
            Wir weisen auf unsere Stornobedingungen (Punkt 4. der Teilnahmebedingungen) hin.<br><br>

            Die Ersatztermine sind ausschließlich für wetter- oder trainerbedingte Verschiebungen vorgesehen. Bitte
            halten Sie sich die Ersatztermine ebenfalls frei!<br><br>

            Bitte beachten Sie unsere 2 Standorte:
            Radlerhaus (Nähe Theresienwiese, U-Bahn-Station "Goetheplatz") und Riem (U-Bahn-Station "Messestadt Ost").
            Kurse, die im
            Kurscode "RIEM" stehen haben, finden in Riem statt, für alle anderen Kurse ist der Treffpunkt das Radlerhaus
            in der
            Platenstr.<br><br>

            Sie finden die Kurse auch in unserem
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