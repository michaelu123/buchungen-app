<?php

use App\Models\Saisonkarten\BasisDaten;
use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use App\Models\Saisonkarten\Buchung;

new class extends Component implements HasSchemas {
    // noinspection PhpUnusedAliasInspection
    /** @use \Filament\Schemas\Concerns\InteractsWithSchemas */
    use \Filament\Schemas\Concerns\InteractsWithSchemas;
    protected const _TRAITS = [\Filament\Schemas\Concerns\InteractsWithSchemas::class];

    protected static ?string $model = Buchung::class;
    public array $data = [];
    public BasisDaten $basisdaten;

    public function mount(): void
    {
        $this->form->fill();
        $this->basisdaten = BasisDaten::first();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->belowLabel("Die Saisonkarte wird Ihnen an diese Adresse zugeschickt. Bitte geben Sie eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsname')
                    ->belowLabel("Name des ADFC-Mitglieds, für den die Saisonkarte ausgestellt werden soll.")
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->belowLabel("Hier bitte die ADFC-Mitgliedsnummer des Mitglieds angeben (8 Ziffern).")
                    ->rules("digits:8")
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
aus dieser Anmeldung von meinem o.a. Bankkonto bei Fälligkeit unter Angabe der Mandatsreferenz „ADFC-M-SK“ einzuziehen. 
Zugleich weise ich mein Kreditinstitut an, vom ADFC München e.V. auf mein Konto gezogene SEPA-Lastschrift einzulösen. 
Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. 
Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.
EOD
                    ))
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
            <p class="lg:text-5xl text-2xl">Bestellung einer Saisonkarte</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Mit diesem Formular können Sie eine Saisonkarte für Tagestouren des ADFC München bestellen.
            Für die Karte wird ein Betrag von {{ $this->basisdaten->betrag }}€ per Lastschrift eingezogen.
            Halten Sie dafür bitte Ihre IBAN-Kontonummer bereit.
            Die Saisonkarte wird an die angegebene Email-Adresse gesendet.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Bestellen
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament::section>