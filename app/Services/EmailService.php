<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function send(string $recipient, string $subject, string $notificationType, Mailable $mailable): bool
    {
        try {
            Mail::mailer('resend')
                ->to($recipient)
                ->send($mailable);

            EmailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'notification_type' => $notificationType,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Throwable $e) {
            EmailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'notification_type' => $notificationType,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendNow(string $recipient, string $subject, string $notificationType, Mailable $mailable): bool
    {
        try {
            Mail::mailer('resend')
                ->to($recipient)
                ->send($mailable);

            EmailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'notification_type' => $notificationType,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Throwable $e) {
            EmailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'notification_type' => $notificationType,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendTestEmail(string $recipient): bool
    {
        $mailable = new \App\Mail\TestEmailMail();

        return $this->sendNow($recipient, 'Test Email from BPA Asset Management', 'test', $mailable);
    }

    public function isConfigured(): bool
    {
        return !empty(config('services.resend.key'));
    }

    public function healthCheck(): array
    {
        $results = [
            'resend_api_key' => !empty(config('services.resend.key')),
            'mail_mailer' => config('mail.default'),
            'mail_from_address' => !empty(config('mail.from.address')),
            'queue_connection' => config('queue.default'),
            'last_email_sent' => null,
            'recent_failures' => 0,
        ];

        $lastSent = EmailLog::where('status', 'sent')->latest()->first();
        if ($lastSent) {
            $results['last_email_sent'] = $lastSent->sent_at?->toIso8601String();
        }

        $results['recent_failures'] = EmailLog::where('status', 'failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return $results;
    }
}
