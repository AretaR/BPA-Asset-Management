@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-tag me-2"></i> {{ $category->name }}
    </h1>
    <div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @can('update', $category)
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        @endcan
        @can('delete', $category)
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
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
                <i class="fas fa-info-circle me-2"></i> Category Details
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <label class="text-muted small">Name</label>
                    <div class="fw-bold">{{ $category->name }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Assets Count</label>
                    <div class="fw-bold">{{ $category->assets_count }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Created</label>
                    <div>{{ $category->created_at->format('M d, Y H:i') }}</div>
                </div>
                @if($category->description)
                <div class="mb-0">
                    <label class="text-muted small">Description</label>
                    <div>{{ $category->description }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-boxes me-2"></i> Assets in this Category ({{ $category->assets->count() }})
            </div>
            <div class="card-body text-white">
                @if($category->assets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asset Tag</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->assets as $asset)
                            <tr>
                                <td><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_tag }}</a></td>
                                <td>{{ Str::limit($asset->name, 40) }}</td>
                                <td><span class="badge {{ $asset->status_badge_class }}">{{ ucfirst($asset->status) }}</span></td>
                                <td>{{ $asset->department->name ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No assets in this category</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection