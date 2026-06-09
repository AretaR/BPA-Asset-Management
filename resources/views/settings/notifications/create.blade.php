@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-bell me-2"></i> Create Notification Template
    </h1>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        @include('settings.notifications._sidebar')
    </div>

    <div class="col-lg-9">
        <form action="{{ route('notification-templates.store') }}" method="POST">
            @csrf

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Basic Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trigger_event" class="form-label">Trigger Event <span class="text-danger">*</span></label>
                            <select class="form-select @error('trigger_event') is-invalid @enderror" id="trigger_event" name="trigger_event" required>
                                <option value="">-- Select Event --</option>
                                @foreach($triggerEvents as $value => $label)
                                <option value="{{ $value }}" {{ old('trigger_event') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('trigger_event') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-envelope me-2"></i> Email Content
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject"
                               value="{{ old('subject') }}" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Email Body (HTML) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="12" required>{{ old('body') }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="text-muted small mt-1">
                            <i class="fas fa-info-circle me-1"></i> You can use HTML tags and placeholders. See the placeholders panel below.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-users me-2"></i> Recipients
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="recipient_type" class="form-label">Recipient Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('recipient_type') is-invalid @enderror" id="recipient_type" name="recipient_type" required>
                                @foreach($recipientTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('recipient_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('recipient_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div id="recipient-custom-emails" class="mt-3 d-none">
                        <label for="recipient_emails" class="form-label">Email Addresses</label>
                        <input type="text" class="form-control" id="recipient_emails" name="recipient_emails"
                               value="{{ old('recipient_emails') }}" placeholder="admin@example.com, manager@example.com">
                        <div class="text-muted small mt-1">Comma-separated email addresses.</div>
                    </div>

                    <div id="recipient-roles" class="mt-3 d-none">
                        <label class="form-label">Select Roles</label>
                        @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipient_values[]"
                                   value="{{ $role['id'] }}" id="role_{{ $role['id'] }}"
                                   {{ is_array(old('recipient_values')) && in_array($role['id'], old('recipient_values')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role['id'] }}">{{ $role['name'] }}</label>
                        </div>
                        @endforeach
                    </div>

                    <div id="recipient-users" class="mt-3 d-none">
                        <label class="form-label">Select Users</label>
                        <div style="max-height: 200px; overflow-y: auto;">
                            @foreach($users as $user)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="recipient_values[]"
                                       value="{{ $user->id }}" id="user_{{ $user->id }}"
                                       {{ is_array(old('recipient_values')) && in_array($user->id, old('recipient_values')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_{{ $user->id }}">
                                    {{ $user->name }} <small class="text-muted">({{ $user->email }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-cog me-2"></i> Settings
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active" style="color:#fff;font-weight:600">Active</label>
                        <div class="text-muted small">Enable this template to send notifications when the trigger event occurs.</div>
                    </div>
                </div>
            </div>

            <div id="placeholders-panel" class="card mb-3 d-none">
                <div class="card-header">
                    <i class="fas fa-code me-2"></i> Available Placeholders
                </div>
                <div class="card-body">
                    <p class="text-muted small">Use these placeholders in your subject and body. They will be replaced with actual values when the email is sent.</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Placeholder</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody id="placeholders-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('notification-templates.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Create Template
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('recipient_type').addEventListener('change', function() {
    document.getElementById('recipient-custom-emails').classList.add('d-none');
    document.getElementById('recipient-roles').classList.add('d-none');
    document.getElementById('recipient-users').classList.add('d-none');

    if (this.value === 'custom_emails') {
        document.getElementById('recipient-custom-emails').classList.remove('d-none');
    } else if (this.value === 'roles') {
        document.getElementById('recipient-roles').classList.remove('d-none');
    } else if (this.value === 'users') {
        document.getElementById('recipient-users').classList.remove('d-none');
    }
});

document.getElementById('trigger_event').addEventListener('change', function() {
    const panel = document.getElementById('placeholders-panel');
    const body = document.getElementById('placeholders-body');

    if (!this.value) {
        panel.classList.add('d-none');
        return;
    }

    fetch('{{ route("notification-templates.placeholders") }}?event=' + encodeURIComponent(this.value))
        .then(r => r.json())
        .then(data => {
            body.innerHTML = '';
            for (const [placeholder, description] of Object.entries(data)) {
                body.innerHTML += '<tr><td><code>' + placeholder + '</code></td><td>' + description + '</td></tr>';
            }
            panel.classList.remove('d-none');
        })
        .catch(() => {});
});

const savedType = document.getElementById('recipient_type').value;
if (savedType) {
    document.getElementById('recipient_type').dispatchEvent(new Event('change'));
}

const savedEvent = document.getElementById('trigger_event').value;
if (savedEvent) {
    document.getElementById('trigger_event').dispatchEvent(new Event('change'));
}
</script>
@endpush
@endsection
