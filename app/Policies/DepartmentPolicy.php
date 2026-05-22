<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Department $department): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManageAssets();
    }

    public function update(User $user, Department $department): bool
    {
        return $user->canManageAssets();
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->canManageAssets() && $department->assets()->count() === 0;
    }
}
