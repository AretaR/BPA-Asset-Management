<?php

namespace App\Http\Controllers;

use App\Mail\AssetRequestMail;
use App\Models\ActivityLog;
use App\Models\AssetRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\EmailService;
use App\Services\NotificationTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AssetRequestController extends Controller
{
    protected EmailService $emailService;
    protected NotificationTemplateService $templateService;

    public function __construct(EmailService $emailService, NotificationTemplateService $templateService)
    {
        $this->emailService = $emailService;
        $this->templateService = $templateService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', AssetRequest::class);

        $query = AssetRequest::with('user');

        if (!auth()->user()->hasPermissionTo('asset-requests.approve')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_name', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(15);

        return view('asset-requests.index', compact('requests'));
    }

    public function create()
    {
        $this->authorize('create', AssetRequest::class);

        return view('asset-requests.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', AssetRequest::class);

        $validated = $request->validate([
            'asset_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'reason' => ['required', 'string', 'max:2000'],
            'priority' => ['required', 'in:low,medium,high'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = auth()->id();

        $assetRequest = AssetRequest::create($validated);

        ActivityLog::logAction('created', $assetRequest);

        $this->sendNotification('submitted', $assetRequest);

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request submitted successfully.');
    }

    public function show(AssetRequest $assetRequest)
    {
        $this->authorize('view', $assetRequest);

        $assetRequest->load('user', 'approver', 'issuer');

        return view('asset-requests.show', compact('assetRequest'));
    }

    public function updateStatus(Request $request, AssetRequest $assetRequest)
    {
        $this->authorize('approve', AssetRequest::class);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,issued,cancelled'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $assetRequest->status;
        $oldValues = $assetRequest->toArray();

        if ($validated['status'] === 'approved') {
            $assetRequest->approved_by = auth()->id();
            $assetRequest->approved_at = now();
        }

        if ($validated['status'] === 'issued') {
            $assetRequest->issued_by = auth()->id();
            $assetRequest->issued_at = now();
        }

        if ($validated['status'] === 'cancelled' && $assetRequest->user_id === auth()->id()) {
            // User can cancel their own pending request
        }

        $assetRequest->status = $validated['status'];

        if (!empty($validated['remarks'])) {
            $assetRequest->remarks = $validated['remarks'];
        }

        $assetRequest->save();

        $newValues = $assetRequest->toArray();
        ActivityLog::logAction('updated', $assetRequest, $oldValues, $newValues);

        $this->sendNotification($validated['status'], $assetRequest);

        $message = match ($validated['status']) {
            'approved' => 'Request approved successfully.',
            'rejected' => 'Request rejected.',
            'issued' => 'Asset marked as issued.',
            'cancelled' => 'Request cancelled.',
            default => 'Status updated.',
        };

        return redirect()->route('asset-requests.index')
            ->with('success', $message);
    }

    protected function sendNotification(string $action, AssetRequest $assetRequest): void
    {
        try {
            $this->templateService->sendForEvent("asset-request.{$action}", [
                'assetRequest' => $assetRequest,
                'actor' => auth()->user(),
            ]);

            $recipients = [];

            if ($action === 'submitted') {
                $adminEmails = Setting::get('email_notification_recipients', '');
                if (!empty($adminEmails)) {
                    $recipients = array_map('trim', explode(',', $adminEmails));
                }

                $admins = User::whereIn('role', ['super_admin', 'admin'])->get();
                foreach ($admins as $admin) {
                    if (!in_array($admin->email, $recipients)) {
                        $recipients[] = $admin->email;
                    }
                }

                if (empty($recipients)) {
                    $recipients = [config('mail.from.address')];
                }
            } else {
                $recipients = [$assetRequest->user->email];
            }

            $mailable = new AssetRequestMail($action, $assetRequest, auth()->user());

            foreach (array_unique($recipients) as $recipient) {
                $this->emailService->send(
                    $recipient,
                    $mailable->envelope()->subject,
                    "asset-request.{$action}",
                    $mailable
                );
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send asset request notification: ' . $e->getMessage());
        }
    }
}
