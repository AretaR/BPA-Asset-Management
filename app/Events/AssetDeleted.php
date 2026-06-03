<?php

namespace App\Events;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Asset $asset;
    public ?User $actor;

    public function __construct(Asset $asset, ?User $actor = null)
    {
        $this->asset = $asset;
        $this->actor = $actor;
    }
}
