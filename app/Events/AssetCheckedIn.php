<?php

namespace App\Events;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetCheckedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Asset $asset;
    public ?User $previousUser;
    public ?User $actor;
    public ?string $notes;

    public function __construct(Asset $asset, ?User $previousUser = null, ?User $actor = null, ?string $notes = null)
    {
        $this->asset = $asset;
        $this->previousUser = $previousUser;
        $this->actor = $actor;
        $this->notes = $notes;
    }
}
