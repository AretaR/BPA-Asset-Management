@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-bell me-2"></i> {{ $notificationTemplate->name }}
    </h1>
    <div>
        <a href="{{ route('notification-templates.edit', $notificationTemplate) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i> Edit Template
        </a>
        <a href="{{ route('notification-templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        @include('settings.notifications._sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Template Details
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td>{{ $notificationTemplate->name }}</td>
                    </tr>
                    @if($notificationTemplate->description)
                    <tr>
                        <th>Description</th>
                        <td>{{ $notificationTemplate->description }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Trigger Event</th>
                        <td>
                            <span class="badge bg-info">{{ $notificationTemplate->trigger_event_label }}</span>
                            <code class="ms-2">{{ $notificationTemplate->trigger_event }}</code>
                        </td>
                    </tr>
                    <tr>
                        <th>Recipient Type</th>
                        <td>{{ $notificationTemplate->recipient_type_label }}</td>
                    </tr>
                    <tr>
                        <th>Recipients</th>
                        <td>
                            @if(is_array($notificationTemplate->recipient_values) && count($notificationTemplate->recipient_values) > 0)
                                {{ implode(', ', $notificationTemplate->recipient_values) }}
                            @else
                                <em class="text-muted">None configured</em>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $notificationTemplate->is_active ? 'success' : 'warning' }}">
                                {{ $notificationTemplate->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{ $notificationTemplate->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $notificationTemplate->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-envelope me-2"></i> Email Subject
            </div>
            <div class="card-body">
                <code>{{ $notificationTemplate->subject }}</code>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-file-alt me-2"></i> Email Body (HTML)
            </div>
            <div class="card-body">
                <pre class="mb-0" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap; word-break: break-all;">{{ $notificationTemplate->body }}</pre>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-code me-2"></i> Available Placeholders
            </div>
            <div class="card-body">
                <p class="text-muted small">These placeholders are available for the selected trigger event:</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Placeholder</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($placeholders as $placeholder => $description)
                            <tr>
                                <td><code>{{ $placeholder }}</code></td>
                                <td>{{ $description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-eye me-2"></i> Preview & Test
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Preview</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-info" onclick="previewTemplate({{ $notificationTemplate->id }})">
                                <i class="fas fa-eye me-2"></i> Render Preview
                            </button>
                        </div>
                    </div>
                </div>
                <div id="preview-result" class="mt-3 d-none">
                    <div class="border rounded p-3 bg-dark">
                        <div class="mb-2"><strong>Subject:</strong> <span id="preview-subject"></span></div>
                        <hr>
                        <div id="preview-body"></div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="test_recipient" class="form-label">Send Test Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="test_recipient" placeholder="Enter recipient email"
                                   value="{{ auth()->user()->email }}">
                            <button type="button" class="btn btn-outline-primary" onclick="sendTestEmail({{ $notificationTemplate->id }})">
                                <i class="fas fa-paper-plane me-2"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
                <div id="test-result" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewTemplate(id) {
    const result = document.getElementById('preview-result');
    result.classList.remove('d-none');
    result.innerHTML = '<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin me-2"></i> Generating preview...</div>';

    fetch('{{ route("notification-templates.preview", $notificationTemplate) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        result.innerHTML = '<div class="border rounded p-3 bg-dark">' +
            '<div class="mb-2"><strong>Subject:</strong> <span id="preview-subject" class="text-white">' + data.subject + '</span></div>' +
            '<hr>' +
            '<div id="preview-body" class="text-white">' + data.body + '</div>' +
            '</div>';
    })
    .catch(() => {
        result.innerHTML = '<div class="alert alert-danger py-2 mb-0">Failed to generate preview.</div>';
    });
}

function sendTestEmail(id) {
    const recipient = document.getElementById('test_recipient').value;
    const result = document.getElementById('test-result');

    if (!recipient) {
        result.innerHTML = '<div class="alert alert-danger py-2 mb-0">Please enter a recipient email address.</div>';
        return;
    }

    result.innerHTML = '<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin me-2"></i> Sending test email...</div>';

    fetch('{{ route("notification-templates.test", $notificationTemplate) }}', {
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
            result.innerHTML = '<div class="alert alert-success py-2 mb-0"><i class="fas fa-check-circle me-2"></i> ' + data.message + '</div>';
        } else {
            result.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i> ' + data.message + '</div>';
        }
    })
    .catch(() => {
        result.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i> Request failed.</div>';
    });
}
</script>
@endpush
@endsection
