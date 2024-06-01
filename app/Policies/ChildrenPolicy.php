<?php

namespace App\Policies;

use App\Models\Children;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChildrenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('children_access');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Children $children): bool
    {
        return $user->hasPermission('children_view');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('children_create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Children $children): bool
    {
        return $user->hasPermission('children_edit');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Children $children): bool
    {
        return $user->hasPermission('children_delete');

    }

}
