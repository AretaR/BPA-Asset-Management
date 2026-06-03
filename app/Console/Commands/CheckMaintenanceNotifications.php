<?php

namespace App\Console\Commands;

use App\Events\MaintenanceDue;
use App\Events\MaintenanceOverdue;
use App\Models\Asset;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMaintenanceNotifications extends Command
{
    protected $signature = 'email:check-maintenance';
    protected $description = 'Check assets for upcoming or overdue maintenance and send notifications';

    public function handle(): int
    {
        if (Setting::get('email_notifications_enabled', 'true') !== 'true') {
            $this->info('Email notifications are disabled. Skipping.');
            return self::SUCCESS;
        }

        $notifyMaintenance = Setting::get('notify_maintenance', 'true');
        if ($notifyMaintenance !== 'true') {
            $this->info('Maintenance notifications are disabled. Skipping.');
            return self::SUCCESS;
        }

        $now = Carbon::now();
        $dueDateThreshold = $now->copy()->addDays(7);
        $overdueThreshold = $now->copy()->subDay();

        $dueAssets = Asset::where('status', Asset::STATUS_MAINTENANCE)
            ->whereNotNull('warranty_expiry')
            ->whereDate('warranty_expiry', '<=', $dueDateThreshold)
            ->whereDate('warranty_expiry', '>', $now)
            ->get();

        foreach ($dueAssets as $asset) {
            MaintenanceDue::dispatch(
                $asset,
                $asset->warranty_expiry?->format('Y-m-d'),
                'Maintenance is due within 7 days.'
            );
            $this->info("Dispatched MaintenanceDue for asset: {$asset->name}");
        }

        $overdueAssets = Asset::where('status', Asset::STATUS_MAINTENANCE)
            ->whereNotNull('warranty_expiry')
            ->whereDate('warranty_expiry', '<', $now)
            ->get();

        foreach ($overdueAssets as $asset) {
            MaintenanceOverdue::dispatch(
                $asset,
                $asset->warranty_expiry?->format('Y-m-d'),
                'Maintenance is overdue.'
            );
            $this->info("Dispatched MaintenanceOverdue for asset: {$asset->name}");
        }

        if ($dueAssets->isEmpty() && $overdueAssets->isEmpty()) {
            $this->info('No maintenance notifications needed.');
        }

        return self::SUCCESS;
    }
}
