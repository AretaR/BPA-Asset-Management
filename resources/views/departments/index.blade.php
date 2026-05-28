@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-building me-2"></i> Departments
    </h1>
    @can('create', \App\Models\Department::class)
    <a href="{{ route('departments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Department
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Departments ({{ $departments->total() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Manager</th>
                        <th class="d-none d-md-table-cell">Location</th>
                        <th>Assets Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td><strong>{{ $department->code }}</strong></td>
                        <td>
                            <a href="{{ route('departments.show', $department) }}">
                                {{ $department->name }}
                            </a>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $department->manager ?? 'N/A' }}</td>
                        <td class="d-none d-md-table-cell">{{ Str::limit($department->location, 30) }}</td>
                        <td><span class="badge bg-info">{{ $department->assets_count }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('departments.show', $department) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $department)
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $department)
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-building fa-3x mb-3"></i>
                            <p>No departments found. @can('create', \App\Models\Department::class)<a href="{{ route('departments.create') }}">Create one</a>@endcan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection