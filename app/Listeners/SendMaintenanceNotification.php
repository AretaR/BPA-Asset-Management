<?php

namespace App\Listeners;

use App\Events\MaintenanceDue;
use App\Events\MaintenanceOverdue;
use App\Mail\MaintenanceNotificationMail;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\NotificationTemplateService;

class SendMaintenanceNotification
{
    public function __construct(
        protected EmailService $emailService,
        protected NotificationTemplateService $templateService
    ) {}

    public function handleDue(MaintenanceDue $event): void
    {
        $this->templateService->sendForEvent('maintenance.due', [
            'asset' => $event->asset,
            'due_date' => $event->dueDate,
            'notes' => $event->notes,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'maintenance_due';

        foreach ($recipients as $recipient) {
            $mailable = new MaintenanceNotificationMail($action, $event->asset, $event->dueDate, $event->notes);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "maintenance.$action",
                $mailable
            );
        }
    }

    public function handleOverdue(MaintenanceOverdue $event): void
    {
        $this->templateService->sendForEvent('maintenance.overdue', [
            'asset' => $event->asset,
            'due_date' => $event->dueDate,
            'notes' => $event->notes,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'maintenance_overdue';

        foreach ($recipients as $recipient) {
            $mailable = new MaintenanceNotificationMail($action, $event->asset, $event->dueDate, $event->notes);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "maintenance.$action",
                $mailable
            );
        }
    }

    protected function getRecipients(): array
    {
        $recipients = Setting::get('email_notification_recipients', '');
        if (empty($recipients)) {
            $adminEmail = Setting::get('email_from_address', '');
            return $adminEmail ? [$adminEmail] : [];
        }

        return array_map('trim', explode(',', $recipients));
    }
}
