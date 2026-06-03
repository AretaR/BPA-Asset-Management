<?php

namespace App\Events;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetCheckedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Asset $asset;
    public ?User $assignedUser;
    public ?User $actor;
    public ?string $notes;

    public function __construct(Asset $asset, ?User $assignedUser = null, ?User $actor = null, ?string $notes = null)
    {
        $this->asset = $asset;
        $this->assignedUser = $assignedUser;
        $this->actor = $actor;
        $this->notes = $notes;
    }
}
