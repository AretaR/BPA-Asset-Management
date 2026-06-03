@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-history me-2"></i> Email Logs
    </h1>
    <div>
        <form action="{{ route('email-logs.clear') }}" method="POST" class="d-inline"
              onsubmit="return confirm('Are you sure you want to clear all email logs?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash me-2"></i> Clear All
            </button>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $stats['total'] }}</h5>
                <small>Total Emails</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $stats['sent'] }}</h5>
                <small>Sent</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $stats['queued'] }}</h5>
                <small>Queued</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-danger">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $stats['failed'] }}</h5>
                <small>Failed</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> Log Entries
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by recipient or subject..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Queued</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->recipient }}</td>
                            <td>{{ Str::limit($log->subject, 50) }}</td>
                            <td><code>{{ $log->notification_type }}</code></td>
                            <td>
                                @if($log->status === 'sent')
                                    <span class="badge bg-success">Sent</span>
                                @elseif($log->status === 'queued')
                                    <span class="badge bg-warning text-dark">Queued</span>
                                @elseif($log->status === 'failed')
                                    <span class="badge bg-danger" title="{{ $log->error_message }}">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($log->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $log->sent_at ? $log->sent_at->format('M j, Y g:i A') : '-' }}</td>
                            <td>
                                <a href="{{ route('email-logs.show', $log) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('email-logs.destroy', $log) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this log entry?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No email logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection