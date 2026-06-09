<?php

namespace App\Services;

use App\Mail\DynamicTemplateMail;
use App\Models\NotificationTemplate;
use App\Models\Setting;

class NotificationTemplateService
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    public function sendForEvent(string $triggerEvent, array $data = []): void
    {
        if (Setting::get('email_notifications_enabled', 'true') !== 'true') {
            return;
        }

        $templates = NotificationTemplate::active()->forEvent($triggerEvent)->get();

        foreach ($templates as $template) {
            $this->sendTemplate($template, $data);
        }
    }

    public function sendTemplate(NotificationTemplate $template, array $data = []): void
    {
        $recipients = $template->resolveRecipients();
        if (empty($recipients)) {
            return;
        }

        $placeholders = $this->buildPlaceholders($data);

        $mailable = new DynamicTemplateMail($template, $placeholders);

        foreach ($recipients as $recipient) {
            $this->emailService->send(
                $recipient,
                $mailable->envelope()->subject,
                $template->trigger_event,
                $mailable
            );
        }
    }

    public function buildPlaceholders(array $data): array
    {
        $placeholders = [];

        if (isset($data['asset'])) {
            $asset = $data['asset'];
            $placeholders['asset_name'] = $asset->name ?? '';
            $placeholders['asset_tag'] = $asset->asset_tag ?? '';
            $placeholders['asset_serial'] = $asset->serial_number ?? '';
            $placeholders['asset_status'] = $asset->status ?? '';
            $placeholders['category_name'] = $asset->category?->name ?? '';
            $placeholders['department_name'] = $asset->department?->name ?? '';
        }

        if (isset($data['user'])) {
            $user = $data['user'];
            $placeholders['user_name'] = $user->name ?? '';
            $placeholders['user_email'] = $user->email ?? '';
            $placeholders['user_role'] = $user->role ?? '';
        }

        if (isset($data['actor'])) {
            $placeholders['actor_name'] = $data['actor']->name ?? '';
        }

        if (isset($data['assetRequest'])) {
            $ar = $data['assetRequest'];
            $placeholders['request_asset_name'] = $ar->asset_name ?? '';
            $placeholders['request_requester'] = $ar->requester?->name ?? $ar->requester_name ?? '';
            $placeholders['request_status'] = $ar->status ?? '';
            $placeholders['request_notes'] = $ar->notes ?? '';
        }

        if (isset($data['due_date'])) {
            $placeholders['due_date'] = $data['due_date'];
        }

        if (isset($data['notes'])) {
            $placeholders['notes'] = $data['notes'];
        }

        $placeholders['company_name'] = Setting::companyName();
        $placeholders['company_email'] = Setting::companyEmail() ?? '';

        return $placeholders;
    }
}
