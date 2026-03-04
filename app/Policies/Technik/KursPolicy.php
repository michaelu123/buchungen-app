<?php

namespace App\Policies\Technik;

use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("TK");
    }
}
