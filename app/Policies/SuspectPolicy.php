<?php

namespace App\Policies;

use App\Models\Suspect;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SuspectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('suspect_access');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Suspect $suspect): bool
    {
        return $user->hasPermission('suspect_view');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('suspect_create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Suspect $suspect): bool
    {
        return $user->hasPermission('suspect_edit');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Suspect $suspect): bool
    {
        return $user->hasPermission('suspect_delete');
    }
}
