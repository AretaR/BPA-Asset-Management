<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?User $actor;

    public function __construct(User $user, ?User $actor = null)
    {
        $this->user = $user;
        $this->actor = $actor;
    }
}
