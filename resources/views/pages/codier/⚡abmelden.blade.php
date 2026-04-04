<?php

use App\Models\Codier\Buchung;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

new class extends Component {
    public string $msg = "";

    public function mount(string $encid): void
    {
        $id = Crypt::decryptString($encid);
        $buchung = Buchung::with("termin")->find($id);

        if ($buchung == null) {
            $this->msg = "Die Terminbuchung existiert nicht oder nicht mehr.";
            return;
        }
        $termin = Carbon::parse($buchung->termin->datum)->translatedFormat('D, d.m') . " um " . $buchung->uhrzeit;
        if (isset($buchung->notiz)) {
            $this->msg = "Ihre Terminbuchung vom " . $termin . " ist schon storniert.";
        } else {
            $buchung->update(["notiz" => "storniert am " . now()]);
            $this->msg = "Ihre Terminbuchung vom " . $termin . " wurde storniert.";
        }
    }
    //
};
?>

<div class="flex flex-col items-center justify-center">
    <h1 class="text-xl font-bold">Bestätigung Ihrer Stornierung</h1>

    <p>{{ $msg }}</p>
</div>