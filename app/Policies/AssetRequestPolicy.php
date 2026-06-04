<?php

namespace App\Policies;

use App\Models\AssetRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('asset-requests.view');
    }

    public function view(User $user, AssetRequest $assetRequest): bool
    {
        if ($user->id === $assetRequest->user_id) {
            return true;
        }

        return $user->hasPermissionTo('asset-requests.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('asset-requests.create');
    }

    public function update(User $user, AssetRequest $assetRequest): bool
    {
        return $user->hasPermissionTo('asset-requests.approve');
    }

    public function delete(User $user, AssetRequest $assetRequest): bool
    {
        return $user->hasPermissionTo('asset-requests.approve');
    }

    public function approve(User $user): bool
    {
        return $user->hasPermissionTo('asset-requests.approve');
    }
}
