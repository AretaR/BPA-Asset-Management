<?php

namespace App\Providers;

use App\Events\AssetCheckedIn;
use App\Events\AssetCheckedOut;
use App\Events\AssetCreated;
use App\Events\AssetDeleted;
use App\Events\AssetUpdated;
use App\Events\MaintenanceDue;
use App\Events\MaintenanceOverdue;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Listeners\SendAssetNotification;
use App\Listeners\SendMaintenanceNotification;
use App\Listeners\SendUserNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            [SendUserNotification::class, 'handleCreated'],
        ],
        UserUpdated::class => [
            [SendUserNotification::class, 'handleUpdated'],
        ],
        UserDeleted::class => [
            [SendUserNotification::class, 'handleDeleted'],
        ],
        AssetCreated::class => [
            [SendAssetNotification::class, 'handleCreated'],
        ],
        AssetUpdated::class => [
            [SendAssetNotification::class, 'handleUpdated'],
        ],
        AssetDeleted::class => [
            [SendAssetNotification::class, 'handleDeleted'],
        ],
        AssetCheckedOut::class => [
            [SendAssetNotification::class, 'handleCheckedOut'],
        ],
        AssetCheckedIn::class => [
            [SendAssetNotification::class, 'handleCheckedIn'],
        ],
        MaintenanceDue::class => [
            [SendMaintenanceNotification::class, 'handleDue'],
        ],
        MaintenanceOverdue::class => [
            [SendMaintenanceNotification::class, 'handleOverdue'],
        ],
    ];

    public function shouldBeDiscoverable(): bool
    {
        return true;
    }
}
