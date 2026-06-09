<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Mail\UserNotificationMail;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\NotificationTemplateService;
use Illuminate\Support\Facades\Mail;

class SendUserNotification
{
    public function __construct(
        protected EmailService $emailService,
        protected NotificationTemplateService $templateService
    ) {}

    public function handleCreated(UserCreated $event): void
    {
        $this->templateService->sendForEvent('user.created', [
            'user' => $event->user,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getNotificationRecipients();
        $action = 'created';

        foreach ($recipients as $recipient) {
            $mailable = new UserNotificationMail($action, $event->user);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "user.$action",
                $mailable
            );
        }
    }

    public function handleUpdated(UserUpdated $event): void
    {
        $this->templateService->sendForEvent('user.updated', [
            'user' => $event->user,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getNotificationRecipients();
        $action = 'updated';

        foreach ($recipients as $recipient) {
            $mailable = new UserNotificationMail($action, $event->user, $event->changes);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "user.$action",
                $mailable
            );
        }
    }

    public function handleDeleted(UserDeleted $event): void
    {
        $this->templateService->sendForEvent('user.deleted', [
            'user' => $event->user,
            'actor' => $event->actor,
        ]);

        if (!Setting::get('email_notifications_enabled', true)) {
            return;
        }

        $recipients = $this->getNotificationRecipients();
        $action = 'deleted';

        foreach ($recipients as $recipient) {
            $mailable = new UserNotificationMail($action, $event->user);
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                "user.$action",
                $mailable
            );
        }
    }

    protected function getNotificationRecipients(): array
    {
        $recipients = Setting::get('email_notification_recipients', '');
        if (empty($recipients)) {
            $adminEmail = Setting::get('email_from_address', '');
            return $adminEmail ? [$adminEmail] : [];
        }

        return array_map('trim', explode(',', $recipients));
    }
}
