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
        return $user->hasPermissionTo('assets.view');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->hasPermissionTo('assets.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('assets.create');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->hasPermissionTo('assets.edit');
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->hasPermissionTo('assets.delete');
    }

    public function checkout(User $user, Asset $asset): bool
    {
        return $user->hasPermissionTo('assets.checkout') && $asset->status === Asset::STATUS_AVAILABLE;
    }

    public function checkin(User $user, Asset $asset): bool
    {
        return $user->hasPermissionTo('assets.checkin') && $asset->status === Asset::STATUS_ASSIGNED;
    }
}
