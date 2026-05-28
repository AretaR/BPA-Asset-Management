@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-users me-2"></i> Users
    </h1>
    @can('create', \App\Models\User::class)
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i> Add New User
    </a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-filter me-2"></i> Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Name, email, employee ID...">
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Users ({{ $users->total() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="d-none d-md-table-cell">Employee ID</th>
                        <th class="d-none d-lg-table-cell">Department</th>
                        <th>Role</th>
                        <th>Assets</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover; border: 1px solid rgba(0,0,0,0.1);">
                                <a href="{{ route('users.show', $user) }}">
                                    <strong>{{ $user->name }}</strong>
                                </a>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="d-none d-md-table-cell">{{ $user->employee_id ?? 'N/A' }}</td>
                        <td class="d-none d-lg-table-cell">{{ $user->department->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $user->role_badge_class }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td><span class="badge bg-info">{{ $user->assets_count }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $user)
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $user)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection