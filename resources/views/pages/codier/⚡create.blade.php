<?php

use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Checkbox;
use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;
use Carbon\Carbon;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Unique;

new class extends Component implements HasSchemas {
    // noinspection PhpUnusedAliasInspection
    /** @use \Filament\Schemas\Concerns\InteractsWithSchemas */
    use \Filament\Schemas\Concerns\InteractsWithSchemas;
    protected const _TRAITS = [\Filament\Schemas\Concerns\InteractsWithSchemas::class];

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    private function nochFrei($beginn, $ende, array $reserviert): array
    {
        $dtBeginn = new \DateTime("2000-01-01 " . $beginn);
        $min10 = new \DateInterval("PT10M");
        $min30 = new \DateInterval("PT30M");
        $dtEnde = new \DateTime("2000-01-01 " . $ende);
        $dtEnde = $dtEnde->sub($min30);
        $frei = [];
        for ($dt = $dtBeginn; $dt <= $dtEnde; $dt = $dt->add($min10)) {
            $uhrZeit = $dt->format("H:i");
            if (!in_array($uhrZeit, $reserviert)) {
                $frei[$uhrZeit] = $uhrZeit;
            }
        }
        return $frei;
    }

    private function uhrzeiten($termin_id, Collection $termine): array
    {
        if ($termin_id == null)
            return [];
        $frei = $termine->filter(fn($t) => $t["id"] == $termin_id);
        return $frei->first()["frei"];
    }

    public function form(Schema $schema): Schema
    {
        $termine = Termin::with("buchungen")
            ->whereNull("notiz")
            ->get()
            ->map(function (Termin $termin): array {
                $reserviert = $termin->buchungen()->get()->map(fn($b) => $b->uhrzeit)->toArray();
                $frei = $this->nochFrei($termin->beginn, $termin->ende, $reserviert);
                return [
                    "id" => $termin->id,
                    "datum" => $termin->datum,
                    "beginn" => $termin->beginn,
                    "ende" => $termin->ende,
                    "reserviert" => $reserviert,
                    "frei" => $frei,
                ];
            })
            ->reject(fn($t) => empty($t["frei"]));
        $termine = new Collection($termine);
        $termineOptions = $termine->mapWithKeys(function (array $t) {
            return [$t["id"] => Carbon::parse($t["datum"])->translatedFormat('D, d.m.y') . " von " . $t["beginn"] . " bis " . $t["ende"]];
        });
        return $schema
            ->components([
                Radio::make("termin_id")
                    ->label("Termin")
                    ->belowLabel(fn()
                        => $termine->isEmpty()
                        ? "Leider gibt es aktuell keine freien Termine!"
                        : "Ich möchte mich für folgenden Termin anmelden:")
                    ->options($termineOptions)
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['uhrzeit'])
                    ->required(),
                Radio::make("uhrzeit")
                    ->inline()
                    ->required()
                    ->options(fn(Get $get) => $this->uhrzeiten($get('termin_id'), $termine))
                    ->unique(
                        Buchung::class,
                        'uhrzeit',
                        modifyRuleUsing: fn(Unique $rule, Get $get): Unique => $rule->where('termin_id', $get('termin_id'))
                    )
                    ->validationMessages([
                        'unique' => 'Die Uhrzeit wurde inzwischen vergeben, bitte wählen Sie eine andere.',
                        'in' => 'Die Uhrzeit wurde inzwischen vergeben, bitte wählen Sie eine andere.',
                    ])
                    ->label("Uhrzeit"),
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
                TextInput::make('email')
                    ->belowLabel("Bitte geben Sie Ihre E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->belowLabel("Falls Sie ADFC-Mitglied sind, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                    ->rules("digits:8"),
                Checkbox::make("datenschutzOk")
                    ->required()
                    ->default(true)
                    ->accepted()
                    ->label(new HtmlString("Ich habe die <a href='https://muenchen.adfc.de/datenschutz' target='_blank' class='underline'>Datenschutzbedingungen</a> zur Kenntnis genommen und willige hiermit ein.")),
                Checkbox::make("emailOk")
                    ->required()
                    ->default(true)
                    ->accepted()
                    ->label("Ich habe meine E-Mail-Adresse erneut auf Korrektheit überprüft.")
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
            <p class="lg:text-5xl text-2xl">Buchung eines Termins für eine Fahrrad-Codierung</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Bitte füllen Sie für die Anmeldung dieses Formular <strong>sorgfältig</strong> aus.<br>
            Bitte beachten Sie, dass pro Termin nur ein Rad codiert werden kann. Bitte reservieren Sie für jedes von
            Ihnen zu codierende Rad daher einen eigenen Termin.<br><br>

            Alle mit einem * gekennzeichneten Felder sind Pflichtfelder.<br><br>

            Diese Daten werden ausschließlich zur Generierung Ihres persönlichen Codier-Codes herangezogen, nur bis zum
            ausgewählten
            Codiertermin vom ADFC München gespeichert und danach umgehend gelöscht. <br><br>
            Sie finden die Termine auch in unserem
            <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank"
                href="https://touren-termine.adfc.de/suche?eventType=Termin&includeSubsidiary=true&includedTags=6&unitKey=152059">
                Termin-Portal
            </a>.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Termin reservieren
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament::section>