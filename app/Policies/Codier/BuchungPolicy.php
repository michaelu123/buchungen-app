<?php

namespace App\Policies\Codier;

use App\Policies\BasePolicy;

class BuchungPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("CODIER");
    }
}
