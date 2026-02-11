<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

    #[On('echo:sampleChannel,Test')]
    public function dump()
    {
        dd("dumped from sample component");
    }
};
?>

<div>
    Sample Livewire Component
</div>