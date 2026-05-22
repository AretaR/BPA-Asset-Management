<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Asset $asset): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManageAssets();
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->canManageAssets();
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->canManageAssets();
    }

    public function checkout(User $user, Asset $asset): bool
    {
        return $user->canAssignAssets() && $asset->status === Asset::STATUS_AVAILABLE;
    }

    public function checkin(User $user, Asset $asset): bool
    {
        return $user->canAssignAssets() && $asset->status === Asset::STATUS_ASSIGNED;
    }
}
