<?php

use Livewire\Component;

new class extends Component {
    //
};
?>


<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        <div class="flex flex-row justify-between items-center">
            <p class="lg:text-5xl text-2xl">Anmeldungsformulare zu Kursen des ADFC München</p>
            <img src="/ADFC_MUENCHEN.PNG" alt="">
        </div>
    </x-slot>
    <div>
        <div class="flex flex-col gap-4 text-2xl underline items-center justify-center">
            <a href="{{  route('rfsabuchung') }}">Radfahrschule Anfänger-Kurse</a>
            <a href="{{  route('rfsfbuchung')}}">Radfahrschule Fahrsicherheitstraining</a>
            <a href="{{  route('rfsfpbuchung')}}">Radfahrschule Fahrpraxis-Kurse</a>
            <a href=" {{  route('tkbuchung')}}">Technik-Kurse</a>
        </div>
        <p class="mb-10">
            Sie finden die Kurse auch in unserem
            <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" target="_blank"
                href="https://touren-termine.adfc.de/suche?fromNow=true&eventType=Termin&includedTags=11&latLng=48.1351253%2C11.5819806&place=M%C3%BCnchen">
                Termin-Portal
            </a>.
        </p>

        <x-filament-actions::modals />
    </div>
</x-filament::section>