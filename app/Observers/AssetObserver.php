<?php

namespace App\Observers;

use App\Events\AssetCheckedIn;
use App\Events\AssetCheckedOut;
use App\Events\AssetCreated;
use App\Events\AssetDeleted;
use App\Events\AssetUpdated;
use App\Models\Asset;
use App\Models\User;

class AssetObserver
{
    public function created(Asset $asset): void
    {
        AssetCreated::dispatch($asset, auth()->user());
    }

    public function updated(Asset $asset): void
    {
        $dirty = $asset->getDirty();
        $original = $asset->getOriginal();

        if (isset($dirty['status']) && isset($dirty['assigned_to'])) {
            $statusChanged = $dirty['status'];
            $oldStatus = $original['status'] ?? null;

            if ($statusChanged === Asset::STATUS_ASSIGNED && $oldStatus !== Asset::STATUS_ASSIGNED) {
                $assignedUser = $asset->assigned_to ? User::find($asset->assigned_to) : null;
                AssetCheckedOut::dispatch($asset, $assignedUser, auth()->user());
                return;
            }

            if ($statusChanged === Asset::STATUS_AVAILABLE && $oldStatus === Asset::STATUS_ASSIGNED) {
                $previousUser = $original['assigned_to'] ? User::find($original['assigned_to']) : null;
                AssetCheckedIn::dispatch($asset, $previousUser, auth()->user());
                return;
            }
        }

        $changes = [];
        foreach ($dirty as $field => $value) {
            if (in_array($field, ['updated_at', 'qr_code'])) continue;
            $oldVal = $original[$field] ?? null;
            if ($oldVal !== $value) {
                $changes[$field] = [$oldVal, $value];
            }
        }

        if (!empty($changes)) {
            AssetUpdated::dispatch($asset, auth()->user(), $changes);
        }
    }

    public function deleted(Asset $asset): void
    {
        AssetDeleted::dispatch($asset, auth()->user());
    }
}
