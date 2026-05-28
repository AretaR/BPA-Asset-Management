@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-tags me-2"></i> Categories
    </h1>
    @can('create', \App\Models\Category::class)
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Category
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Categories ({{ $categories->total() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Assets Count</th>
                        <th class="d-none d-sm-table-cell">Description</th>
                        <th class="d-none d-sm-table-cell">Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <a href="{{ route('categories.show', $category) }}">
                                <strong>{{ $category->name }}</strong>
                            </a>
                        </td>
                        <td><span class="badge bg-info">{{ $category->assets_count }}</span></td>
                        <td class="d-none d-sm-table-cell">{{ Str::limit($category->description, 50) }}</td>
                        <td class="d-none d-sm-table-cell">{{ $category->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $category)
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $category)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
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
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-tags fa-3x mb-3"></i>
                            <p>No categories found. @can('create', \App\Models\Category::class)<a href="{{ route('categories.create') }}">Create one</a>@endcan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection