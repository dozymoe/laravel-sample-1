<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $object): bool
    {
        foreach ($object->getRoleNames() as $role) {
            if ($user->hasPermissionTo("view {$role}s")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $object): bool
    {
        foreach ($object->getRoleNames() as $role) {
            if ($user->hasPermissionTo("edit {$role}s")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $object): bool
    {
        return $this->update($user, $object);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $object): bool
    {
        return $this->update($user, $object);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $object): bool
    {
        return $this->update($user, $object);
    }
}
