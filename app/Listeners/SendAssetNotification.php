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

class SendAssetNotification
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    public function handleCreated(AssetCreated $event): void
    {
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
