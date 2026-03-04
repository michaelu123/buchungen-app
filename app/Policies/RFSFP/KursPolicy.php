<?php

namespace App\Policies\RFSFP;

use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("RFSFP");
    }
}
