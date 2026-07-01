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
use Filament\Schemas\Components\Utilities\Get;
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


    public function form(Schema $schema): Schema
    {
        $termine = Buchung::getTermine();
        $termineOptions = Buchung::getTermineOptions($termine);
        return $schema
            ->components([
                Radio::make("termin_id")
                    ->label("Termin")
                    ->belowLabel(fn(): string
                        => $termine->isEmpty()
                        ? "Leider gibt es aktuell keine freien Termine!"
                        : "Ich möchte mich für folgenden Termin anmelden:")
                    ->options($termineOptions)
                    ->disableOptionWhen(fn(int $value): bool => $value < 0)
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['uhrzeit'])
                    ->required(),
                Radio::make("uhrzeit")
                    ->inline()
                    ->required()
                    ->options(fn(Get $get): array => Buchung::uhrzeiten($get('termin_id'), "", $termine))
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
                    ->placeholder('Wählen Sie eine Anrede')
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
                TextInput::make('strasse')
                    ->label('Straße')
                    ->required(),
                TextInput::make('hsnr')
                    ->label('Hausnummer')
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
                // TextInput::make('mitgliedsnummer')
                //     ->belowLabel("Falls Sie ADFC-Mitglied sind, bitte hier die Mitgliedsnummer angeben, für den ermäßigten Preis. Sonst leer lassen.")
                //     ->rules("digits:8"),
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
            <p class="lg:text-5xl text-2xl">Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <p class="mb-10">
            Mit diesem Formular können Sie sich für Termine anmelden, die im Radlerhaus München, Platenstr. 4
            stattfinden.<br>
            Wenn Sie ein Datum selektieren, werden die noch offenen Termine angezeigt.<br>
            Termine, die nicht im Radlerhaus stattfinden, werden der Vollständigkeit halber angezeigt, sind aber nicht
            selektierbar<br><br>
            Bitte füllen Sie für die Anmeldung dieses Formular <strong>sorgfältig</strong> aus.<br>
            Bitte beachten Sie, dass pro Termin nur ein Rad codiert werden kann. Bitte reservieren Sie für jedes von
            Ihnen zu codierende Rad daher einen eigenen Termin.<br><br>

            Alle mit einem * gekennzeichneten Felder sind Pflichtfelder.
            Ihre Daten dienen ausschließlich der Ermittlung Ihres persönlichen Codes.<br><br>

            Sie finden die Termine auch in unserem
            <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank"
                href="https://touren-termine.adfc.de/suche?eventType=Termin&includeSubsidiary=true&includedTags=6&unitKey=152059">
                Termin-Portal
            </a>.
        </p>
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                <x-filament::loading-indicator wire:loading wire:target="create" class="h-10 w-10" />
                Termin reservieren
            </x-filament::button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament::section>