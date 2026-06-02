@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-boxes me-2"></i> Assets
    </h1>
    <div>
        @if(auth()->user()->hasPermissionTo('scanner.access'))
        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#scanModal" title="Scan Barcode">
            <i class="fas fa-camera me-1"></i> Scan
        </button>
        @endif
        @can('create', \App\Models\Asset::class)
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Asset
        </a>
        @endcan
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-filter me-2"></i> Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('assets.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by name, tag, serial...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="department" class="form-label">Department</label>
                <select class="form-select" id="department" name="department">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i> All Assets ({{ $assets->total() }})</span>
        @if(auth()->user()->hasPermissionTo('assets.view'))
        <div class="btn-group">
            <a href="{{ route('assets.export', array_merge(request()->all(), ['preview' => 1])) }}" class="btn btn-sm btn-info" target="_blank" onclick="event.preventDefault(); window.open(this.href, 'print-preview', 'width=1200,height=800'); return false;">
                <i class="fas fa-eye me-1"></i> Preview
            </a>
            <a href="{{ route('assets.export', array_merge(request()->all(), ['format' => 'xlsx'])) }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>
            <a href="{{ route('assets.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Asset Tag</th>
                        <th class="d-none d-sm-table-cell">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th class="d-none d-lg-table-cell">Department</th>
                        <th>Assigned To</th>
                        <th class="d-none d-sm-table-cell">Status</th>
                        <th>Purchase Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>
                            <a href="{{ route('assets.show', $asset) }}">
                                <strong>{{ $asset->asset_tag }}</strong>
                            </a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            @if($asset->image_url)
                                <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}"
                                     class="rounded" style="width:36px;height:36px;object-fit:cover;">
                            @else
                                <span class="text-muted"><i class="fas fa-image fa-lg"></i></span>
                            @endif
                        </td>
                        <td>{{ Str::limit($asset->name, 30) }}</td>
                        <td>{{ $asset->category->name ?? 'N/A' }}</td>
                        <td class="d-none d-lg-table-cell">{{ $asset->department->name ?? 'N/A' }}</td>
                        <td>{{ $asset->assignedUser->name ?? 'Unassigned' }}</td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge {{ $asset->status_badge_class }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </td>
                        <td>${{ number_format((float) ($asset->purchase_cost ?? 0), 2) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $asset)
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $asset)
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No assets found. @can('create', \App\Models\Asset::class)<a href="{{ route('assets.create') }}">Create one</a>@endcan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $assets->withQueryString()->links() }}
        </div>
    </div>
</div>
@if(auth()->user()->hasPermissionTo('scanner.access'))
@include('assets.partials.scan-modal')
@endif
@endsection
