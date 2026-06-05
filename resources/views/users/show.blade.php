@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-user me-2"></i> {{ $user->name }}
    </h1>
    <div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        @endcan
        @can('delete', $user)
        @if(!$user->isSuperAdmin())
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                <i class="fas fa-trash me-2"></i> Delete
            </button>
        </form>
        @endif
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i> User Details
            </div>
            <div class="card-body text-white">
                <div class="text-center mb-4">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle border border-3 border-light shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Name</label>
                    <div class="fw-bold">{{ $user->name }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <div>{{ $user->email }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Employee ID</label>
                    <div>{{ $user->employee_id ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Phone</label>
                    <div>{{ $user->phone ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Position</label>
                    <div>{{ $user->position ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Department</label>
                    <div>{{ $user->department->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Role</label>
                    <div><span class="badge {{ $user->role_badge_class }}">{{ $user->role_display_name }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Assigned Assets</label>
                    <div class="fw-bold">{{ $user->assets_count }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small">Member Since</label>
                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-boxes me-2"></i> Assigned Assets ({{ $user->assignedAssets->count() }})
            </div>
            <div class="card-body text-white">
                @if($user->assignedAssets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asset Tag</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->assignedAssets as $asset)
                            <tr>
                                <td><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_tag }}</a></td>
                                <td>{{ Str::limit($asset->name, 40) }}</td>
                                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                <td>{{ $asset->department->name ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No assigned assets</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection