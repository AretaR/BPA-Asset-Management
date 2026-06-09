<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?User $actor;
    public array $changes;
    public bool $passwordOnly;

    public function __construct(User $user, ?User $actor = null, array $changes = [], bool $passwordOnly = false)
    {
        $this->user = $user;
        $this->actor = $actor;
        $this->changes = $changes;
        $this->passwordOnly = $passwordOnly;
    }
}
