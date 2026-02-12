<?php

use Livewire\Component;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Checkbox;
use App\Models\Technik\Kurs;
use App\Models\Technik\Buchung;

new class extends Component implements HasSchemas {
    use InteractsWithSchemas;
    protected static ?string $model = Buchung::class;
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
        // $this->form->inlineLabel();
    }

    public function form(Schema $schema): Schema
    {
        $arr1 = Kurs::select(["nummer", "titel", "datum", "restplätze"])
            ->whereNull("notiz")
            ->where("restplätze", ">", 0)
            ->get()->toArray();
        $arr2 = collect($arr1)->mapWithKeys(function (array $item) {
            return [$item["nummer"] => $item["nummer"] . ": " . $item["titel"] . " am " . date("d.m.Y", strtotime($item["datum"])) . ", freie Plätze: " . $item["restplätze"] . ")"];
        })->all();
        return $schema
            // ->inlineLabel()
            ->components([
                TextInput::make('email')
                    ->belowLabel("Die Bestätigung der Buchung erfolgt per E-Mail. Bitte geben Sie eine gültige E-Mail-Adresse an.")
                    ->email()
                    ->required(),
                TextInput::make('mitgliedsnummer')
                    ->belowLabel("Falls Sie ADFC-Mitglied sind, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                    ->rules("digits:8")
                // ->validationMessages([
                //     'decimal' => 'Die Mitgliedsnummer besteht aus 8 Ziffern.',
                //])
                ,
                // Select::make('kursnummer')
                //     ->belowLabel("Bitte wählen Sie den Kurs, für den Sie sich anmelden möchten.")
                //     ->label("Kursname")
                //     ->options(
                //         $arr2,
                //     )
                //     ->required(),
                Radio::make("kursnummer")
                    ->label("Ich möchte mich für folgenden Kurs anmelden:")
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
                    ->belowLabel("Bitte bestätigen Sie, dass die Lastschrift von dem angegebenen Konto genehmigt ist. Ohne diese Genehmigung können wir Ihre Anmeldung nicht bearbeiten.")
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
            Anmeldung zu einem Technikkurs
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Mit diesem Formular können Sie sich für einen Technikkurs des ADFC München anmelden. Wir brauchen Ihre
            Email-Adresse für die Bestätigungs-Mails. Die Kursgebühr wird per Lastschrift eingezogen. Halten Sie dafür
            bitte
            Ihre IBAN-Kontonummer bereit.
            Die Kurse kosten 20€ für Nicht-ADFC-Mitglieder, 10€ für ADFC-Mitglieder. Sie finden die Kurse auch in
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