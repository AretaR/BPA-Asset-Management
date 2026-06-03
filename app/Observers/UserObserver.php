<?php

namespace App\Observers;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        UserCreated::dispatch($user, auth()->user());
    }

    public function updated(User $user): void
    {
        $changes = [];
        foreach ($user->getDirty() as $field => $value) {
            if (in_array($field, ['updated_at'])) continue;
            $original = $user->getOriginal($field);
            if ($original !== $value) {
                $changes[$field] = [$original, $value];
            }
        }

        if (!empty($changes)) {
            UserUpdated::dispatch($user, auth()->user(), $changes);
        }
    }

    public function deleted(User $user): void
    {
        UserDeleted::dispatch($user, auth()->user());
    }
}
