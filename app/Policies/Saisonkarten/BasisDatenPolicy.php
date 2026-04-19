<?php

namespace App\Policies\Saisonkarten;

use App\Policies\BasePolicy;

class BasisDatenPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("ADMIN");
    }
}
