<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
  public function __construct(public string $role)
  {
  }

  public function permits(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["role"]);
    if ($roles->contains("ADMIN"))
      return true;
    if ($roles->contains($this->role))
      return true;
    if ($roles->contains('RFS'))
      return str_starts_with($this->role, 'RFS');
    return false;
  }

}
