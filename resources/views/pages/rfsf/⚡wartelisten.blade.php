<?php

use Livewire\Component;

new class extends Component {
    protected string $view = 'filament.widgets.usage-widget';
    protected int|string|array $columnSpan = 2;
    // public string $content;

    // public function mount(): void
    // {
    //     $this->content = file_get_contents(public_path('intro.md'));
    // }
};
?>

<div>
    <x-filament::section class="max-w-7xl mx-auto items-center justify-center">
        <x-slot name="heading">
            <div class="flex flex-row justify-between items-center">
                <p class="lg:text-5xl text-2xl">Warteliste zu Fahrsicherheits-Kursen des ADFC München</p>
                <img src="/ADFC_MUENCHEN.PNG" alt="">
            </div>
        </x-slot>
        <div>
            <div class="flex flex-col gap-4 text-2xl underline items-center justify-center">
                <a href="{{  route('warteliste_rfsfg') }}">Warteliste für Fahrsicherheitstraining-Grundkurse</a>
                <a href="{{  route('warteliste_rfsfa') }}">Warteliste für Fahrsicherheitstraining-Aufbaukurse</a>
                <a href="{{  route('warteliste_rfsfs') }}">Warteliste für Fahrsicherheitstraining-Senior:innenkurse</a>
            </div>
            <x-filament-actions::modals />
        </div>
    </x-filament::section>

    {{--  
    <x-filament::section class="mt-10 max-w-7xl mx-auto items-center justify-center">
        <div class="fi-prose lg:prose-xl max-w-none dark:prose-invert">
            <h1>Dieser Text erscheint nur während der Testphase!</h1>
            {!! str($content)->markdown() !!}
        </div>
    </x-filament::section>
    --}}
</div>