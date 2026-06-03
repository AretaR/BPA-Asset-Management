@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-heartbeat me-2"></i> Email Health
    </h1>
    <div>
        <a href="{{ route('email-logs.index') }}" class="btn btn-outline-info me-2">
            <i class="fas fa-history me-2"></i> View Logs
        </a>
        <a href="{{ route('settings.email') }}" class="btn btn-outline-secondary">
            <i class="fas fa-cog me-2"></i> Email Settings
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-stethoscope me-2"></i> Configuration Status
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th>Mail Driver</th>
                        <td>
                            @if($config['mail_mailer'] === 'resend')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> {{ $config['mail_mailer'] }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $config['mail_mailer'] }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Resend API Key</th>
                        <td>
                            @if($config['resend_key_set'])
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Configured</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Missing</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>From Address</th>
                        <td><code>{{ $config['mail_from_address'] ?: 'Not set' }}</code></td>
                    </tr>
                    <tr>
                        <th>From Name</th>
                        <td>{{ $config['mail_from_name'] ?: 'Not set' }}</td>
                    </tr>
                    <tr>
                        <th>Queue Connection</th>
                        <td><code>{{ $config['queue_connection'] }}</code></td>
                    </tr>
                    <tr>
                        <th>Notifications Enabled</th>
                        <td>
                            @if($config['email_notifications_enabled'] === 'true' || $config['email_notifications_enabled'] === '1')
                                <span class="badge bg-success">Enabled</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Notification Recipients</th>
                        <td>
                            @if($config['email_notification_recipients'])
                                <code>{{ $config['email_notification_recipients'] }}</code>
                            @else
                                <span class="text-muted">Not configured (will use from address)</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-bar me-2"></i> Delivery Statistics
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3 class="text-success mb-0">{{ $stats['total_sent'] }}</h3>
                            <small class="text-muted">Total Sent</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3 class="text-danger mb-0">{{ $stats['total_failed'] }}</h3>
                            <small class="text-muted">Total Failed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3 class="text-info mb-0">{{ $stats['sent_today'] }}</h3>
                            <small class="text-muted">Sent Today</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h3 class="text-warning mb-0">{{ $stats['failed_today'] }}</h3>
                            <small class="text-muted">Failed Today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-paper-plane me-2"></i> Send Test Email
    </div>
    <div class="card-body">
        <form action="{{ route('email-health.test') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-8">
                <label for="recipient" class="form-label">Recipient Email</label>
                <input type="email" class="form-control @error('recipient') is-invalid @enderror"
                       id="recipient" name="recipient"
                       value="{{ old('recipient', auth()->user()->email) }}" required>
                @error('recipient')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane me-2"></i> Send Test
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-clock me-2"></i> Recent Activity
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Recipient</th>
                        <th>Subject</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->diffForHumans() }}</td>
                            <td>{{ $log->recipient }}</td>
                            <td>{{ Str::limit($log->subject, 40) }}</td>
                            <td>
                                @if($log->status === 'sent')
                                    <span class="badge bg-success">Sent</span>
                                @elseif($log->status === 'failed')
                                    <span class="badge bg-danger" title="{{ $log->error_message }}">Failed</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($log->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No email activity yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection