<?php

namespace App\Policies\RFSF;

use App\Policies\BasePolicy;

class BuchungPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("RFSF");
    }
}
