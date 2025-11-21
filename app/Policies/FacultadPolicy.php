<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Facultad;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacultadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Facultad');
    }

    public function view(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('View:Facultad');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Facultad');
    }

    public function update(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('Update:Facultad');
    }

    public function delete(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('Delete:Facultad');
    }

    public function restore(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('Restore:Facultad');
    }

    public function forceDelete(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('ForceDelete:Facultad');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Facultad');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Facultad');
    }

    public function replicate(AuthUser $authUser, Facultad $facultad): bool
    {
        return $authUser->can('Replicate:Facultad');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Facultad');
    }

}