<?php

namespace App\Policies;

use App\Models\User;

class EmailVerifikationPolicy extends BasePolicy
{

  public function __construct()
  {
    parent::__construct("EMAILVERIFIKATION");
  }


  public function permits(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    if ($roles->contains("ADMIN")) {
      return true;
    }
    return !$roles->contains("CODIER");
  }
}
