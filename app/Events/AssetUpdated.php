<?php

namespace App\Events;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Asset $asset;
    public ?User $actor;
    public array $changes;

    public function __construct(Asset $asset, ?User $actor = null, array $changes = [])
    {
        $this->asset = $asset;
        $this->actor = $actor;
        $this->changes = $changes;
    }
}
