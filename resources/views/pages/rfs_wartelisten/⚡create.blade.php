<?php

use App\Models\RFSWartelisten\BasisDaten;
use App\Models\RFSWartelisten\Warteliste;
use Filament\Forms\Components\Checkbox;
use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

new class extends Component implements HasSchemas {
    // noinspection PhpUnusedAliasInspection
    /** @use \Filament\Schemas\Concerns\InteractsWithSchemas */
    use \Filament\Schemas\Concerns\InteractsWithSchemas;

    protected static ?string $model = Warteliste::class;
    public array $data = [];
    public BasisDaten $basisDaten;
    public $kursart = "kursart";
    public $text = "text";

    public function mount(Request $request): void
    {
        $this->basisDaten = BasisDaten::first();

        // Access route name, path, query parameters, etc.
        $routeName = $request->route()?->getName(); // e.g. "warteliste_rfsa"
        $path = $request->path();                   // e.g. "warteliste_rfsa"
        $param = $request->query('foo');

        // Set your component's public properties here:
        match ($routeName) {
            'warteliste_rfsa' => [
                $this->kursart = 'Anfängerkurse',
                $this->text = $this->basisDaten->text_rfsa,
            ],
            'warteliste_rfsfg' => [
                $this->kursart = 'Fahrsicherheitstraining-Grundkurse',
                $this->text = $this->basisDaten->text_rfsfg,
            ],
            'warteliste_rfsfa' => [
                $this->kursart = 'Fahrsicherheitstraining-Aufbaukurse',
                $this->text = $this->basisDaten->text_rfsfa,
            ],
            'warteliste_rfsfs' => [
                $this->kursart = 'Fahrsicherheitstraining-Senior:innenkurse',
                $this->text = $this->basisDaten->text_rfsfs,
            ],
            'warteliste_rfsfp' => [
                $this->kursart = 'Fahrpraxiskurse',
                $this->text = $this->basisDaten->text_rfsfp,
            ],
            default => null,
        };
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->belowLabel("Um Sie benachrichtigen zu können, geben Sie bitte eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                Select::make('anrede')
                    ->placeholder('Wählen Sie eine Anrede')
                    ->options(["Herr" => "Herr", "Frau" => "Frau", "" => "Keine Angabe"]),
                TextInput::make('vorname')
                    ->required(),
                TextInput::make('nachname')
                    ->required(),
                TextInput::make('telefonnr')
                    ->belowLabel("Bitte geben Sie eine Telefonnummer an, unter der wir Sie erreichen können, falls es Rückfragen zu Ihrer Anmeldung gibt.")
                    ->label('Telefon')
                    ->required(),
                Checkbox::make('datenschutzok')
                    ->belowContent(new HtmlString(<<<EOD
                    Ich habe die  <a href="https://muenchen.adfc.de/artikel/rfs-datenschutz" style="text-decoration: underline;">Datenschutzbestimmungen</a> zur Kenntnis genommen und akzeptiere sie.
EOD))
                    ->label('Zustimmung')
                    ->default(false)
                    ->accepted()
                    ->required(),
                Textarea::make("mitteilung")
                    ->belowLabel("Falls Sie uns noch etwas mitteilen möchten:")
                    ->rows(2)
                    ->autosize()
                    ->maxLength(2000),

            ])->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $data["kursart"] = $this->kursart;
        Log::info("data " . json_encode($data));
        Warteliste::create($data);
        redirect()->route('warteliste.ok');
    }
}
?>

<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        <div class="flex flex-row justify-between items-center">
            <p class="lg:text-5xl text-2xl">Warteliste für {{ $this->kursart }} der Radfahrschule München</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-2">
            {{ new HtmlString($this->text) }}
        </p>
        <p class="mb-10">
            Bitte tragen Sie hier Ihre Kontaktdaten ein. <br>
            Sobald im Frühjahr die Kurstermine der neuen Saison feststehen, erhalten Sie von uns eine E-Mail und können
            bevorzugt einen Platz buchen.<br>
            Hinweis: Ihre E-Mail-Adresse und Ihr Name werden ausschließlich zum Zwecke der Benachrichtigung über die
            neuen Kurse gespeichert und unmittelbar nach Benachrichtigung gelöscht.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Eintragen
            </x-filament::button>
        </form>
        <p>
            <br><strong>Datenschutzerklärung gemäß Art. 13 Datenschutz-Grundverordnung (DSGVO):</strong><br>

            Der „Allgemeine Deutsche Fahrrad-Club München e.V.“ speichert die Anmeldedaten nur zur Erfüllung des
            vereinbarten Schulungsverhältnisses.
            Verantwortliche Stelle ist der Verein „Allgemeiner Deutscher Fahrrad-Club München e.V., Platenstraße 4,
            80336 München.
            Die in diesem Formular erhobenen personenbezogenen Daten Name, E-Mail-Adresse, Telefonnummer werden
            ausschließlich genutzt, um Sie über neue Kurse zu informieren. Danach werden sie gelöscht.

            Die Daten werden an Dritte nur bei Vorliegen einer rechtlichen Verpflichtung übermittelt. Die Nutzung zu
            Werbezwecken findet nicht statt. Eine ausführliche Erklärung des Vereins finden Sie unter:
            <a href="https://muenchen.adfc.de/datenschutz"
                style="text-decoration: underline;">https://muenchen.adfc.de/datenschutz</a>
        </p>
        <x-filament-actions::modals />
    </div>
</x-filament::section>