@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-history me-2"></i> Activity Logs
    </h1>
    <a href="{{ route('reports.activity_logs', ['format' => 'pdf']) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf me-2"></i> Export PDF
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-filter me-2"></i> Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.activity_logs') }}" class="row g-3">
            <div class="col-md-3">
                <label for="action" class="form-label">Action</label>
                <select class="form-select" id="action" name="action">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label">User</label>
                <select class="form-select" id="user_id" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('reports.activity_logs') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> Activity Logs
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->user->name }}</td>
                        <td>
                            <span class="badge bg-{{ $activity->action_color }}">
                                <i class="fas fa-{{ $activity->action_icon }} me-1"></i>
                                {{ ucfirst($activity->action) }}
                            </span>
                        </td>
                        <td>
                            @if($activity->model_type)
                                @php
                                    $modelClass = class_basename($activity->model_type);
                                @endphp
                                {{ $modelClass }} #{{ $activity->model_id }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                        <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No activity logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $activities->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection