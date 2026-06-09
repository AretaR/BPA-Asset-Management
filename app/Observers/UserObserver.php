<?php

namespace App\Observers;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Models\User;

class UserObserver
{
    protected array $sensitiveFields = [
        'password',
        'password_confirmation',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'avatar',
    ];

    public function created(User $user): void
    {
        UserCreated::dispatch($user, auth()->user());
    }

    public function updated(User $user): void
    {
        $changes = [];
        $dirty = $user->getDirty();
        $hasSensitiveOnly = true;

        foreach ($dirty as $field => $value) {
            if (in_array($field, ['updated_at'])) {
                continue;
            }

            if (in_array($field, $this->sensitiveFields)) {
                continue;
            }

            $hasSensitiveOnly = false;
            $original = $user->getOriginal($field);

            if ($original !== $value) {
                $changes[$field] = [$original, $value];
            }
        }

        if (!empty($changes)) {
            UserUpdated::dispatch($user, auth()->user(), $changes);
        } elseif ($hasSensitiveOnly && $user->isDirty('password')) {
            UserUpdated::dispatch($user, auth()->user(), [], true);
        }
    }

    public function deleted(User $user): void
    {
        UserDeleted::dispatch($user, auth()->user());
    }
}
