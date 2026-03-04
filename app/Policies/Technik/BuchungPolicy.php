<?php

namespace App\Policies\Technik;

use App\Policies\BasePolicy;

class BuchungPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("TK");
    }
}
