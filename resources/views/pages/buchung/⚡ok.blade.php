<?php

use Livewire\Component;

new class extends Component {
    public string $msg;
};
?>

<div class="flex flex-col items-center justify-center">
    <h1 class="text-xl font-bold">Bestätigung</h1>

    <p>Danke für Ihre Bestellung!</p>
    <p>{{ session('msg') }}</p>
</div>