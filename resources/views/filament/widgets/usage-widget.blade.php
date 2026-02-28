<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
        <div class="fi-prose lg:prose-xl max-w-none dark:prose-invert">
            {!! str($content)->markdown() !!}
        </div>

    </x-filament::section>
</x-filament-widgets::widget>