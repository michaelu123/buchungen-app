<?php

namespace App\Policies\Codier;

use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("CODIER");
    }
}
