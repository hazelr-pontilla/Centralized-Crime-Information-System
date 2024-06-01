<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Victim;
use Illuminate\Auth\Access\Response;

class VictimPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('victim_access');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Victim $victim): bool
    {
        return $user->hasPermission('victim_view');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('victim_create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Victim $victim): bool
    {
        return $user->hasPermission('victim_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Victim $victim): bool
    {
        return $user->hasPermission('victim_delete');
    }
}
