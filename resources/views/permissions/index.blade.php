@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-key me-2"></i> Permissions
    </h1>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Permission
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Permissions ({{ $permissions->total() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Assigned Roles</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                    <tr>
                        <td>
                            <strong>{{ $permission->name }}</strong>
                            @if($permission->description)
                                <div class="small text-muted">{{ Str::limit($permission->description, 80) }}</div>
                            @endif
                        </td>
                        <td><code>{{ $permission->slug }}</code></td>
                        <td><span class="badge bg-secondary">{{ $permission->roles_count }}</span></td>
                        <td>
                            @if($permission->isSystemPermission())
                                <span class="badge bg-dark">System</span>
                            @else
                                <span class="badge bg-light text-dark border">Custom</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @unless($permission->isSystemPermission())
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this permission?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-key fa-3x mb-3"></i>
                            <p class="mb-0">No permissions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $permissions->links() }}
        </div>
    </div>
</div>
@endsection
