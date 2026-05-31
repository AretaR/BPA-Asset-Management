@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-user-shield me-2"></i> Roles
    </h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Role
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Roles ({{ $roles->total() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ $role->name }}</strong>
                            @if($role->description)
                                <div class="small text-muted">{{ Str::limit($role->description, 80) }}</div>
                            @endif
                        </td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $role->users_count }}</span></td>
                        <td><span class="badge bg-secondary">{{ $role->permissions_count }}</span></td>
                        <td>
                            @if($role->isSystemRole())
                                <span class="badge bg-dark">System</span>
                            @else
                                <span class="badge bg-light text-dark border">Custom</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @unless($role->isSystemRole())
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this role?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-user-shield fa-3x mb-3"></i>
                            <p class="mb-0">No roles found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
