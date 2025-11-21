<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Prestamo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrestamoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Prestamo');
    }

    public function view(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('View:Prestamo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Prestamo');
    }

    public function update(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('Update:Prestamo');
    }

    public function delete(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('Delete:Prestamo');
    }

    public function restore(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('Restore:Prestamo');
    }

    public function forceDelete(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('ForceDelete:Prestamo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Prestamo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Prestamo');
    }

    public function replicate(AuthUser $authUser, Prestamo $prestamo): bool
    {
        return $authUser->can('Replicate:Prestamo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Prestamo');
    }

}