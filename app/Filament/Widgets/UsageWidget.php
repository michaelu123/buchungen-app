<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UsageWidget extends Widget
{
    protected string $view = 'filament.widgets.usage-widget';
    protected int|string|array $columnSpan = 2;
    public string $content = 'init xxxxxx';

    public function mount(): void
    {
        $this->content = file_get_contents(public_path('intro.md'));
    }
}
