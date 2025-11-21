<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tesis;
use Illuminate\Auth\Access\HandlesAuthorization;

class TesisPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tesis');
    }

    public function view(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('View:Tesis');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tesis');
    }

    public function update(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('Update:Tesis');
    }

    public function delete(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('Delete:Tesis');
    }

    public function restore(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('Restore:Tesis');
    }

    public function forceDelete(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('ForceDelete:Tesis');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tesis');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tesis');
    }

    public function replicate(AuthUser $authUser, Tesis $tesis): bool
    {
        return $authUser->can('Replicate:Tesis');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tesis');
    }

}