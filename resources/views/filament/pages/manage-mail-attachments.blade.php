<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::actions :actions="$this->getFormActions()" alignment="center" class="mt-4" />
        </div>
    </form>
</x-filament-panels::page>