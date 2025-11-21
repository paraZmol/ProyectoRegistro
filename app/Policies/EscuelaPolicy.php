<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Escuela;
use Illuminate\Auth\Access\HandlesAuthorization;

class EscuelaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Escuela');
    }

    public function view(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('View:Escuela');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Escuela');
    }

    public function update(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('Update:Escuela');
    }

    public function delete(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('Delete:Escuela');
    }

    public function restore(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('Restore:Escuela');
    }

    public function forceDelete(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('ForceDelete:Escuela');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Escuela');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Escuela');
    }

    public function replicate(AuthUser $authUser, Escuela $escuela): bool
    {
        return $authUser->can('Replicate:Escuela');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Escuela');
    }

}