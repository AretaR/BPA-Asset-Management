@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-cog me-2"></i> Settings
    </h1>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="list-group">
            <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-building me-2"></i> Company Settings
            </a>
            <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.email') ? 'active' : '' }}">
                <i class="fas fa-envelope me-2"></i> Email Settings
            </a>
            <a href="{{ route('email-logs.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-history me-2"></i> Email Logs
            </a>
            <a href="{{ route('email-health.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-heartbeat me-2"></i> Email Health
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-envelope me-2"></i> Email Sender Settings
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email_from_address" class="form-label">From Address</label>
                            <input type="email" class="form-control" id="email_from_address" name="email_from_address"
                                   value="{{ old('email_from_address', \App\Models\Setting::emailFrom()) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email_from_name" class="form-label">From Name</label>
                            <input type="text" class="form-control" id="email_from_name" name="email_from_name"
                                   value="{{ old('email_from_name', \App\Models\Setting::emailFromName()) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-bell me-2"></i> Notification Settings
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch master-toggle">
                                <input class="form-check-input" type="checkbox" id="email_notifications_enabled"
                                       name="email_notifications_enabled" value="1"
                                       {{ old('email_notifications_enabled', \App\Models\Setting::get('email_notifications_enabled', 'true')) === 'true' || old('email_notifications_enabled', \App\Models\Setting::get('email_notifications_enabled', 'true')) === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications_enabled">
                                    <strong>Enable Email Notifications</strong>
                                </label>
                                <div class="text-muted small">Master switch to enable or disable all email notifications.</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email_notification_recipients" class="form-label">Notification Recipients</label>
                            <input type="text" class="form-control" id="email_notification_recipients"
                                   name="email_notification_recipients"
                                   value="{{ old('email_notification_recipients', \App\Models\Setting::get('email_notification_recipients', '')) }}"
                                   placeholder="admin@bpa.com, manager@bpa.com">
                            <div class="text-muted small">Comma-separated email addresses that will receive all notifications.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded notification-card">
                                    <div class="flex-shrink-0 me-3 text-primary fs-4">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="notify_asset_created"
                                                   name="notify_asset_created" value="1"
                                                   {{ old('notify_asset_created', \App\Models\Setting::get('notify_asset_created', 'true')) === 'true' || old('notify_asset_created', \App\Models\Setting::get('notify_asset_created', 'true')) === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_asset_created">Asset Created</label>
                                        </div>
                                        <small class="notification-desc d-block mt-1">When a new asset is added to the system</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded notification-card">
                                    <div class="flex-shrink-0 me-3 text-warning fs-4">
                                        <i class="fas fa-pen"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="notify_asset_updated"
                                                   name="notify_asset_updated" value="1"
                                                   {{ old('notify_asset_updated', \App\Models\Setting::get('notify_asset_updated', 'true')) === 'true' || old('notify_asset_updated', \App\Models\Setting::get('notify_asset_updated', 'true')) === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_asset_updated">Asset Updated</label>
                                        </div>
                                        <small class="notification-desc d-block mt-1">When asset details are modified</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded notification-card">
                                    <div class="flex-shrink-0 me-3 text-info fs-4">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="notify_asset_checkout"
                                                   name="notify_asset_checkout" value="1"
                                                   {{ old('notify_asset_checkout', \App\Models\Setting::get('notify_asset_checkout', 'true')) === 'true' || old('notify_asset_checkout', \App\Models\Setting::get('notify_asset_checkout', 'true')) === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_asset_checkout">Asset Checkout / Check-in</label>
                                        </div>
                                        <small class="notification-desc d-block mt-1">When an asset is assigned or returned</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded notification-card">
                                    <div class="flex-shrink-0 me-3 text-success fs-4">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="notify_user_created"
                                                   name="notify_user_created" value="1"
                                                   {{ old('notify_user_created', \App\Models\Setting::get('notify_user_created', 'true')) === 'true' || old('notify_user_created', \App\Models\Setting::get('notify_user_created', 'true')) === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_user_created">User Changes</label>
                                        </div>
                                        <small class="notification-desc d-block mt-1">When user accounts are created or updated</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded notification-card">
                                    <div class="flex-shrink-0 me-3 text-danger fs-4">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="notify_maintenance"
                                                   name="notify_maintenance" value="1"
                                                   {{ old('notify_maintenance', \App\Models\Setting::get('notify_maintenance', 'true')) === 'true' || old('notify_maintenance', \App\Models\Setting::get('notify_maintenance', 'true')) === '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_maintenance">Maintenance Alerts</label>
                                        </div>
                                        <small class="notification-desc d-block mt-1">When maintenance is due or overdue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-paper-plane me-2"></i> Send Test Email
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label for="test_recipient" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="test_recipient" name="test_recipient"
                                   placeholder="Enter email to receive test message"
                                   value="{{ old('test_recipient', auth()->user()->email) }}">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-primary" onclick="sendTestEmail()">
                                <i class="fas fa-paper-plane me-2"></i> Send Test Email
                            </button>
                        </div>
                    </div>
                    <div id="testEmailResult" class="mt-2"></div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.notification-card .form-check-label {
    color: #ffffff !important;
    font-weight: 700;
    font-size: 0.9375rem;
}
.notification-card .notification-desc {
    color: #e2e8f0 !important;
    font-size: 0.8125rem;
    line-height: 1.4;
}
.master-toggle .form-check-label {
    color: #ffffff !important;
}
.master-toggle .text-muted {
    color: #e2e8f0 !important;
}
</style>
@endpush

@push('scripts')
<script>
function sendTestEmail() {
    const recipient = document.getElementById('test_recipient').value;
    const result = document.getElementById('testEmailResult');

    if (!recipient) {
        result.innerHTML = '<div class="alert alert-danger py-2 mb-0">Please enter a recipient email address.</div>';
        return;
    }

    result.innerHTML = '<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin me-2"></i> Sending test email...</div>';

    fetch('{{ route("email-health.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ recipient })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            result.innerHTML = '<div class="alert alert-success py-2 mb-0"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';
        } else {
            result.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i>' + data.message + '</div>';
        }
    })
    .catch(() => {
        result.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i> Request failed. Check console for details.</div>';
    });
}
</script>
@endpush
@endsection