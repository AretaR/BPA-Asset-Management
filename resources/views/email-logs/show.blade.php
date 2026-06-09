@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-envelope me-2"></i> Email Log Details
    </h1>
    <a href="{{ route('email-logs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to Logs
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Message Details
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-white" style="width: 25%">Recipient</th>
                        <td>{{ $emailLog->recipient }}</td>
                    </tr>
                    <tr>
                        <th class="text-white">Subject</th>
                        <td>{{ $emailLog->subject }}</td>
                    </tr>
                    <tr>
                        <th class="text-white">Type</th>
                        <td><code>{{ $emailLog->notification_type }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-white">Status</th>
                        <td>
                            @if($emailLog->status === 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($emailLog->status === 'queued')
                                <span class="badge bg-warning text-dark">Queued</span>
                            @elseif($emailLog->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($emailLog->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-white">Resend Message ID</th>
                        <td><code>{{ $emailLog->resend_message_id ?? '-' }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-white">Sent At</th>
                        <td>{{ $emailLog->sent_at ? $emailLog->sent_at->format('F j, Y g:i:s A') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-white">Created At</th>
                        <td>{{ $emailLog->created_at->format('F j, Y g:i:s A') }}</td>
                    </tr>
                    <tr>
                        <th class="text-white">Updated At</th>
                        <td>{{ $emailLog->updated_at->format('F j, Y g:i:s A') }}</td>
                    </tr>
                    @if($emailLog->error_message)
                    <tr>
                        <th class="text-white">Error Message</th>
                        <td>
                            <pre class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $emailLog->error_message }}</pre>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cog me-2"></i> Actions
            </div>
            <div class="card-body">
                <form action="{{ route('email-logs.destroy', $emailLog) }}" method="POST"
                      onsubmit="return confirm('Delete this log entry permanently?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i> Delete Log Entry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection