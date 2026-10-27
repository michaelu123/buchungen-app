<?php

namespace App\Policies\RFSWartelisten;

use App\Policies\BasePolicy;

class BasisDatenPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("RFS");
    }
}
