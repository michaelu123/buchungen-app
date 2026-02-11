<?php

use Livewire\Component;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\TechnikKurs;
use App\Models\TechnikBuchung;

new class extends Component implements HasSchemas {
    use InteractsWithSchemas;
    protected static ?string $model = TechnikBuchung::class;
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
        // $this->form->inlineLabel();
    }

    public function form(Schema $schema): Schema
    {
        $arr1 = TechnikKurs::select(["nummer", "titel"])
            ->whereNull("notiz")
            ->where("restplätze", ">", 0)
            ->get()->toArray();
        $arr2 = collect($arr1)->mapWithKeys(function (array $item) {
            return [$item["nummer"] => $item["nummer"] . " - " . $item["titel"]];
        })->all();
        return $schema
            // ->inlineLabel()
            ->components([
                TextInput::make('email')
                    ->belowLabel("Die Bestätigung der Buchung erfolgt per E-Mail. Bitte gib eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->autofocus()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->belowLabel("Falls Du ADFC-Mitglied bist, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                    ->rules("digits:8")
                // ->validationMessages([
                //     'decimal' => 'Die Mitgliedsnummer besteht aus 8 Ziffern.',
                //])
                ,
                Select::make('kursnummer')
                    ->belowLabel("Bitte wähle den Kurs, für den Du dich anmelden möchtest.")
                    ->label("Kursname")
                    ->options(
                        $arr2,
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
                    ->belowLabel("Bitte gib eine Telefonnummer an, unter der wir Dich erreichen können, falls es Rückfragen zu Deiner Anmeldung gibt.")
                    ->label('Telefon')
                    ->tel()
                    ->required(),
                TextInput::make('kontoinhaber')
                    ->belowLabel("Bitte gib den Namen des Kontoinhabers an, von dem die Lastschrift erfolgen soll.")
                    ->required(),
                TextInput::make('iban')
                    ->belowLabel("Bitte gib die IBAN des Kontos an, von dem die Lastschrift erfolgen soll.")
                    ->rules([
                        fn(): Closure => function ($attribute, $value, Closure $fail) {
                            if (!TechnikBuchung::test_iban($value)) {
                                $fail('Die IBAN ist ungültig.');
                            }
                        },
                    ])
                    ->required(),
                Toggle::make('lastschriftok')
                    ->belowLabel("Bitte bestätige, dass die Lastschrift von dem angegebenen Konto genehmigt ist. Ohne diese Genehmigung können wir Deine Anmeldung nicht bearbeiten.")
                    ->label('Lastschrift genehmigt')
                    ->default(true)
                    ->required(),
            ])->statePath('data');
    }

    public function create(): void
    {
        TechnikBuchung::create($this->form->getState());
        Notification::make()
            ->title('TechnikBuchung created successfully')
            ->success()
            ->send();
        redirect()->route('buchung.ok')->with('msg', "Du erhältst in Kürze eine Bestätigung per E-Mail.");
    }
}
?>

<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        Anmeldung zu einem Technikkurs

    </x-slot>
    <div>
        <p class="mb-10">
            Mit diesem Formular kannst Du Dich für einen Technikkurs des ADFC München anmelden. Wir brauchen Deine
            Email-Adresse für die Bestätigungs-Mails. Die Kursgebühr wird per Lastschrift eingezogen. Halte dafür bitte
            auch Deine
            IBAN-Kontonummer bereit.
            Die Kurse kosten 20€ für Nicht-ADFC-Mitglieder, 10€ für ADFC-Mitglieder. Du findest die Kurse auch in
            unserem
            <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank"
                href="https://touren-termine.adfc.de/suche?fromNow=true&eventType=Termin&includedTags=11&latLng=48.1351253%2C11.5819806&place=M%C3%BCnchen">
                Termin-Portal
            </a>.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Create
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament::section>