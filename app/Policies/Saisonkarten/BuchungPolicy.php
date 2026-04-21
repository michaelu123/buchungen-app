<?php

namespace App\Policies\Saisonkarten;

use App\Policies\BasePolicy;

class BuchungPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("SK");
    }
}
