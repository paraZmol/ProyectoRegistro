<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tablet;
use Illuminate\Auth\Access\HandlesAuthorization;

class TabletPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tablet');
    }

    public function view(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('View:Tablet');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tablet');
    }

    public function update(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('Update:Tablet');
    }

    public function delete(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('Delete:Tablet');
    }

    public function restore(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('Restore:Tablet');
    }

    public function forceDelete(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('ForceDelete:Tablet');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tablet');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tablet');
    }

    public function replicate(AuthUser $authUser, Tablet $tablet): bool
    {
        return $authUser->can('Replicate:Tablet');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tablet');
    }

}