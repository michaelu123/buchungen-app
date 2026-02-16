<?php

namespace App\Policies\Technik;

use App\Models\Technik\Kurs;
use App\Models\User;
use App\Policies\BasePolicy;

class KursPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct("TK");
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
    public function view(User $user, Kurs $kurs): bool
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
    public function update(User $user, Kurs $kurs): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kurs $kurs): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kurs $kurs): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kurs $kurs): bool
    {
        return $this->permits($user);
    }
}
