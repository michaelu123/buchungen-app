<?php

namespace App\Policies\Technik;

use App\Models\Technik\Buchung;
use App\Models\User;

class BuchungPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Buchung $buchung): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Buchung $buchung): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Buchung $buchung): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Buchung $buchung): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Buchung $buchung): bool
    {
        return $user->roles->map(fn($role) => $role["role"])->contains("TK");
    }
}
