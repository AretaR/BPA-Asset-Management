<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Setting;
use App\Services\EmailService;
use Illuminate\Http\Request;

class EmailHealthController extends Controller
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    public function index()
    {
        $health = $this->emailService->healthCheck();

        $config = [
            'mail_mailer' => config('mail.default'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'resend_key_set' => !empty(config('services.resend.key')),
            'queue_connection' => config('queue.default'),
            'email_notifications_enabled' => Setting::get('email_notifications_enabled', true),
            'email_notification_recipients' => Setting::get('email_notification_recipients', ''),
        ];

        $recentLogs = EmailLog::latest()->take(10)->get();

        $stats = [
            'total_sent' => EmailLog::where('status', 'sent')->count(),
            'total_failed' => EmailLog::where('status', 'failed')->count(),
            'total_queued' => EmailLog::where('status', 'queued')->count(),
            'sent_today' => EmailLog::where('status', 'sent')
                ->whereDate('created_at', today())
                ->count(),
            'failed_today' => EmailLog::where('status', 'failed')
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('email-health.index', compact('health', 'config', 'recentLogs', 'stats'));
    }

    public function test(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|email',
        ]);

        $success = $this->emailService->sendTestEmail($validated['recipient']);

        if ($request->expectsJson()) {
            if ($success) {
                return response()->json(['success' => true, 'message' => 'Test email sent successfully to ' . $validated['recipient'] . '. Check your inbox.']);
            }
            return response()->json(['success' => false, 'message' => 'Failed to send test email. Check the email logs for details.'], 500);
        }

        if ($success) {
            return redirect()->back()->with('success', 'Test email sent successfully to ' . $validated['recipient'] . '. Check your inbox.');
        }

        return redirect()->back()->with('error', 'Failed to send test email. Check the email logs for details.');
    }
}
