<?php

namespace App\Listeners;

use App\Events\AssetCheckedIn;
use App\Events\AssetCheckedOut;
use App\Events\AssetCreated;
use App\Events\AssetDeleted;
use App\Events\AssetUpdated;
use App\Mail\AssetNotificationMail;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\NotificationTemplateService;

class SendAssetNotification
{
    public function __construct(
        protected EmailService $emailService,
        protected NotificationTemplateService $templateService
    ) {}

    public function handleCreated(AssetCreated $event): void
    {
        $this->templateService->sendForEvent('asset.created', [
            'asset' => $event->asset,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'created';

        foreach ($recipients as $recipient) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "asset.$action",
                $mailable
            );
        }
    }

    public function handleUpdated(AssetUpdated $event): void
    {
        $this->templateService->sendForEvent('asset.updated', [
            'asset' => $event->asset,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'updated';

        foreach ($recipients as $recipient) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "asset.$action",
                $mailable
            );
        }
    }

    public function handleDeleted(AssetDeleted $event): void
    {
        $this->templateService->sendForEvent('asset.deleted', [
            'asset' => $event->asset,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'deleted';

        foreach ($recipients as $recipient) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "asset.$action",
                $mailable
            );
        }
    }

    public function handleCheckedOut(AssetCheckedOut $event): void
    {
        $this->templateService->sendForEvent('asset.checked_out', [
            'asset' => $event->asset,
            'actor' => $event->actor,
            'notes' => $event->notes,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'checked_out';

        foreach ($recipients as $recipient) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor, $event->notes);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "asset.$action",
                $mailable
            );
        }

        if ($event->assignedUser?->email) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor, $event->notes);
            $this->emailService->send(
                $event->assignedUser->email,
                $mailable->envelope()->subject,
                "asset.$action",
                $mailable
            );
        }
    }

    public function handleCheckedIn(AssetCheckedIn $event): void
    {
        $this->templateService->sendForEvent('asset.checked_in', [
            'asset' => $event->asset,
            'actor' => $event->actor,
            'notes' => $event->notes,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getRecipients();
        $action = 'checked_in';

        foreach ($recipients as $recipient) {
            $mailable = new AssetNotificationMail($action, $event->asset, $event->actor, $event->notes);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "asset.$action",
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
