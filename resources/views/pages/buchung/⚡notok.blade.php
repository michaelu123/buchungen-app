<?php

use Livewire\Component;

new class extends Component {
    public string $msg;
};
?>

<div class="flex flex-col items-center justify-center">
    <h1 class="text-xl font-bold">Fehlgeschlagen!</h1>

    <p>Leider hat die Buchung nicht geklappt!</p>
    <p>{{ session('msg') }}</p>
</div>