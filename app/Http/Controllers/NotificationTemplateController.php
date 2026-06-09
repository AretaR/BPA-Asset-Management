<?php

namespace App\Http\Controllers;

use App\Models\NotificationTemplate;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NotificationTemplateController extends Controller
{
    public function __construct(
        protected NotificationTemplateService $templateService
    ) {}

    public function index()
    {
        $templates = NotificationTemplate::withTrashed()->orderBy('trigger_event')->orderBy('name')->get();
        $triggerEvents = NotificationTemplate::triggerEvents();

        return view('settings.notifications.index', compact('templates', 'triggerEvents'));
    }

    public function create()
    {
        $triggerEvents = NotificationTemplate::triggerEvents();
        $recipientTypes = NotificationTemplate::recipientTypes();
        $roles = $this->getRoles();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('settings.notifications.create', compact('triggerEvents', 'recipientTypes', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'trigger_event' => 'required|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:custom_emails,roles,users',
            'recipient_values' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($validated['recipient_type'] === 'custom_emails') {
            $validated['recipient_values'] = $request->recipient_emails
                ? array_map('trim', explode(',', $request->recipient_emails))
                : [];
        }

        NotificationTemplate::create($validated);

        return redirect()->route('notification-templates.index')
            ->with('success', 'Notification template created successfully.');
    }

    public function edit(NotificationTemplate $notificationTemplate)
    {
        $triggerEvents = NotificationTemplate::triggerEvents();
        $recipientTypes = NotificationTemplate::recipientTypes();
        $roles = $this->getRoles();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $placeholders = NotificationTemplate::placeholdersFor($notificationTemplate->trigger_event);

        return view('settings.notifications.edit', compact('notificationTemplate', 'triggerEvents', 'recipientTypes', 'roles', 'users', 'placeholders'));
    }

    public function update(Request $request, NotificationTemplate $notificationTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'trigger_event' => 'required|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:custom_emails,roles,users',
            'recipient_values' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($validated['recipient_type'] === 'custom_emails') {
            $validated['recipient_values'] = $request->recipient_emails
                ? array_map('trim', explode(',', $request->recipient_emails))
                : [];
        }

        $notificationTemplate->update($validated);

        return redirect()->route('notification-templates.index')
            ->with('success', 'Notification template updated successfully.');
    }

    public function destroy(NotificationTemplate $notificationTemplate)
    {
        $notificationTemplate->delete();

        return redirect()->route('notification-templates.index')
            ->with('success', 'Notification template deactivated successfully.');
    }

    public function restore($id)
    {
        $template = NotificationTemplate::withTrashed()->findOrFail($id);
        $template->restore();

        return redirect()->route('notification-templates.index')
            ->with('success', 'Notification template restored successfully.');
    }

    public function show(NotificationTemplate $notificationTemplate)
    {
        $placeholders = NotificationTemplate::placeholdersFor($notificationTemplate->trigger_event);

        return view('settings.notifications.show', compact('notificationTemplate', 'placeholders'));
    }

    public function preview(Request $request, NotificationTemplate $notificationTemplate)
    {
        $sampleData = $this->getSampleData($notificationTemplate->trigger_event);
        $placeholders = app(NotificationTemplateService::class)->buildPlaceholders($sampleData);

        $renderedSubject = $this->replacePlaceholders($notificationTemplate->subject, $placeholders);
        $renderedBody = $this->replacePlaceholders($notificationTemplate->body, $placeholders);

        return response()->json([
            'subject' => $renderedSubject,
            'body' => $renderedBody,
        ]);
    }

    public function test(Request $request, NotificationTemplate $notificationTemplate)
    {
        $request->validate(['recipient' => 'required|email']);

        $sampleData = $this->getSampleData($notificationTemplate->trigger_event);
        $placeholders = app(NotificationTemplateService::class)->buildPlaceholders($sampleData);

        $mailable = new \App\Mail\DynamicTemplateMail($notificationTemplate, $placeholders);

        $sent = app(\App\Services\EmailService::class)->send(
            $request->recipient,
            $mailable->envelope()->subject,
            $notificationTemplate->trigger_event . '.test',
            $mailable
        );

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'Test email sent successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send test email.'], 500);
    }

    protected function getRoles(): array
    {
        try {
            if (Schema::hasTable('roles')) {
                return Role::orderBy('name')->get(['id', 'name'])->toArray();
            }
        } catch (\Throwable) {}

        return [
            ['id' => 'super_admin', 'name' => 'Super Admin'],
            ['id' => 'admin', 'name' => 'Admin'],
            ['id' => 'staff', 'name' => 'Staff'],
        ];
    }

    protected function getSampleData(string $triggerEvent): array
    {
        $sample = [];

        if (str_starts_with($triggerEvent, 'asset.')) {
            $sample['asset'] = (object) [
                'name' => 'Sample Asset',
                'asset_tag' => 'AST-001',
                'serial_number' => 'SN-12345',
                'status' => 'available',
                'category' => (object) ['name' => 'Electronics'],
                'department' => (object) ['name' => 'IT Department'],
            ];
        }

        if (str_starts_with($triggerEvent, 'user.')) {
            $sample['user'] = (object) [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'staff',
            ];
        }

        if (str_starts_with($triggerEvent, 'maintenance.')) {
            $sample['asset'] = (object) [
                'name' => 'Sample Asset',
                'asset_tag' => 'AST-001',
                'serial_number' => 'SN-12345',
            ];
            $sample['due_date'] = '2026-07-01';
        }

        if (str_starts_with($triggerEvent, 'asset-request.')) {
            $sample['assetRequest'] = (object) [
                'asset_name' => 'Requested Asset',
                'requester' => (object) ['name' => 'John Doe'],
                'requester_name' => 'John Doe',
                'status' => 'pending',
                'notes' => 'Sample request notes',
            ];
        }

        $sample['actor'] = (object) ['name' => 'Admin User'];

        return $sample;
    }

    protected function replacePlaceholders(string $text, array $placeholders): string
    {
        foreach ($placeholders as $key => $value) {
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        return $text;
    }
}
