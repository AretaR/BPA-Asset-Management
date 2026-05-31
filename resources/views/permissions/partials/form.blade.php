<div class="card">
    <div class="card-header">
        <i class="fas fa-key me-2"></i> Permission Details
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="name" class="form-label">Permission Name *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission?->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Permission Slug *</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $permission?->slug) }}" {{ $permission?->isSystemPermission() ? 'readonly' : '' }} required>
            <div class="form-text">Use dotted keys such as <code>assets.audit</code> or <code>users.export</code>.</div>
            @if($permission?->isSystemPermission())
                <div class="form-text">System permission slugs are fixed because application access checks depend on them.</div>
            @endif
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-0">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $permission?->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> {{ $permission ? 'Update Permission' : 'Create Permission' }}
        </button>
    </div>
</div>
