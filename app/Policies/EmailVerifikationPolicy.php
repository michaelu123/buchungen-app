<?php

namespace App\Policies;

use App\Models\User;

class EmailVerifikationPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return !$roles->isEmpty();
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return !$roles->isEmpty();
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return !$roles->isEmpty();
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return $roles->contains("ADMIN");
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return $roles->contains("ADMIN");
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return $roles->contains("ADMIN");
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user): bool
  {
    $roles = $user->roles->map(fn($role) => $role["name"]);
    return $roles->contains("ADMIN");
  }

}
