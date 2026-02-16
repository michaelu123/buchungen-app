<?php

namespace App\Policies\RFSF;

use App\Models\RFSF\Buchung;
use App\Models\User;
use App\Policies\BasePolicy;

class BuchungPolicy extends BasePolicy
{

    public function __construct()
    {
        parent::__construct("RFSF");
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
    public function view(User $user, Buchung $buchung): bool
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
    public function update(User $user, Buchung $buchung): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Buchung $buchung): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Buchung $buchung): bool
    {
        return $this->permits($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Buchung $buchung): bool
    {
        return $this->permits($user);
    }
}
