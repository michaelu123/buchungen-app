<?php

use Livewire\Component;
use App\Models\EmailVerifikation;

new class extends Component {
    public function mount(string $emailb64): void
    {
        $email = base64_decode($emailb64);
        EmailVerifikation::verifyEmail($email);
    }
};


// http://buchungen-app.test/emailverifikation/bWljaGFlbC51aGxlbmJlcmdAdC1vbmxpbmUuZGU=
?>

<div>
    Danke für die Bestätigung Ihrer E-Mail-Adresse.
</div>