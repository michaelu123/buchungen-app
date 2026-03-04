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
    $roles = $user->roles->map(fn($role) => $role["name"]);
    if ($roles->contains("ADMIN")) {
      return true;
    }
    if ($roles->contains($this->role)) {
      return true;
    }
    if ($roles->contains('RFS')) {
      return str_starts_with($this->role, 'RFS');
    }
    return false;
  }

  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user): bool
  {
    return $this->permits($user);
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user): bool
  {
    return $this->permits($user);
  }
}
