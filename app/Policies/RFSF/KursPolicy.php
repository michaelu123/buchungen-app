<?php

namespace App\Policies\RFSF;

use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("RFSF");
    }
}
