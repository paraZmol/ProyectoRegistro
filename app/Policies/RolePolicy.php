<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Solo el Super Admin puede ver la lista de roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super Admin','Admin']);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole(['Super Admin','Admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super Admin','Admin']);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole(['Super Admin','Admin']);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole(['Super Admin','Admin']);
    }
}