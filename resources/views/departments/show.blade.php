@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-building me-2"></i> {{ $department->name }}
    </h1>
    <div>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @can('update', $department)
        <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        @endcan
        @can('delete', $department)
        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                <i class="fas fa-trash me-2"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Department Details
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <label class="text-muted small">Code</label>
                    <div class="fw-bold">{{ $department->code }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Manager</label>
                    <div>{{ $department->manager ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Location</label>
                    <div>{{ $department->location ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <div>{{ $department->email ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Phone</label>
                    <div>{{ $department->phone ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Assets Count</label>
                    <div class="fw-bold">{{ $department->assets_count }}</div>
                </div>
                @if($department->description)
                <div class="mb-0">
                    <label class="text-muted small">Description</label>
                    <div>{{ $department->description }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-boxes me-2"></i> Assets in this Department ({{ $department->assets->count() }})
            </div>
            <div class="card-body">
                @if($department->assets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asset Tag</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->assets as $asset)
                            <tr>
                                <td><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_tag }}</a></td>
                                <td>{{ Str::limit($asset->name, 40) }}</td>
                                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                <td><span class="badge {{ $asset->status_badge_class }}">{{ ucfirst($asset->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No assets in this department</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection