<?php

use Livewire\Component;
use App\Models\EmailVerifikation;

new class extends Component {
    public function mount(string $param): void
    {
        $arr = base64_decode($param);
        $obj = json_decode($arr);
        $email = $obj->email;
        $class = $obj->class;
        EmailVerifikation::verifyEmail($email, $class);
    }
};


// http://buchungen-app.test/emailverifikation/bWljaGFlbC51aGxlbmJlcmdAdC1vbmxpbmUuZGU=
?>

<div class="flex flex-col items-center justify-center">
    <h1 class="text-xl font-bold">Email verifiziert</h1>

    <p class="mt-10">Danke für die Bestätigung Ihrer E-Mail-Adresse!</p>
</div>