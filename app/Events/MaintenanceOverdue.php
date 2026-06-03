<?php

namespace App\Events;

use App\Models\Asset;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceOverdue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Asset $asset;
    public ?string $dueDate;
    public ?string $notes;

    public function __construct(Asset $asset, ?string $dueDate = null, ?string $notes = null)
    {
        $this->asset = $asset;
        $this->dueDate = $dueDate;
        $this->notes = $notes;
    }
}
