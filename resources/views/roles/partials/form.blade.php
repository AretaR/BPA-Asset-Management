<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-user-shield me-2"></i> Role Details
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="name" class="form-label">Role Name *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role?->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Role Slug *</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $role?->slug) }}" {{ $role?->isSystemRole() ? 'readonly' : '' }} required>
            @if($role?->isSystemRole())
                <div class="form-text">System role slugs are fixed because they are part of application access rules.</div>
            @endif
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-0">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $role?->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-key me-2"></i> Assign Permissions
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($permissions as $group => $groupPermissions)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-uppercase text-muted mb-3">{{ str($group)->replace('_', ' ')->title() }}</h6>
                    @foreach($groupPermissions as $permission)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $assignedPermissions), true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                            <span class="fw-semibold">{{ $permission->name }}</span>
                            <small class="d-block text-muted">{{ $permission->slug }}</small>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> {{ $role ? 'Update Role' : 'Create Role' }}
        </button>
    </div>
</div>
