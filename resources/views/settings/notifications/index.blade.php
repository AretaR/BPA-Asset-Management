@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-bell me-2"></i> Notification Templates
    </h1>
    <a href="{{ route('notification-templates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> New Template
    </a>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        @include('settings.notifications._sidebar')
    </div>

    <div class="col-lg-9">
        @if($templates->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-3">No notification templates yet.</p>
                <a href="{{ route('notification-templates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Your First Template
                </a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> All Templates
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Trigger Event</th>
                                <th>Recipients</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr class="{{ $template->trashed() ? 'table-danger' : '' }}">
                                <td>
                                    <a href="{{ route('notification-templates.show', $template) }}" class="fw-medium text-decoration-none">
                                        {{ $template->name }}
                                    </a>
                                    @if($template->description)
                                    <br><small class="text-muted">{{ Str::limit($template->description, 60) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $triggerEvents[$template->trigger_event] ?? $template->trigger_event }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $template->recipient_type_label }}</span>
                                    <br><small class="text-muted">{{ is_array($template->recipient_values) ? count($template->recipient_values) : 0 }} recipient(s)</small>
                                </td>
                                <td>
                                    @if($template->trashed())
                                        <span class="badge bg-danger">Inactive</span>
                                    @else
                                        <span class="badge bg-{{ $template->is_active ? 'success' : 'warning' }}">
                                            {{ $template->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('notification-templates.show', $template) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('notification-templates.edit', $template) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($template->trashed())
                                        <form action="{{ route('notification-templates.restore', $template->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Deactivate"
                                            onclick="if(confirm('Deactivate this template?')){ this.nextElementSibling.submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form action="{{ route('notification-templates.destroy', $template) }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
