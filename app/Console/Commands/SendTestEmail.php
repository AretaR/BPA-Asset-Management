<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class SendTestEmail extends Command
{
    protected $signature = 'email:test {recipient}';
    protected $description = 'Send a test email to verify configuration';

    public function handle(EmailService $emailService): int
    {
        $recipient = $this->argument('recipient');
        $result = $emailService->sendTestEmail($recipient);

        if ($result) {
            $this->info("Test email sent successfully to {$recipient}");
            return self::SUCCESS;
        }

        $this->error('Failed to send test email. Check email logs for details.');
        return self::FAILURE;
    }
}
