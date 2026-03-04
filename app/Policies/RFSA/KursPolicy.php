<?php

namespace App\Policies\RFSA;

use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("RFSA");
    }
}
