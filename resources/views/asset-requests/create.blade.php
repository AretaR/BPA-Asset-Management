@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-plus-circle me-2"></i> Request Asset
    </h1>
    <a href="{{ route('asset-requests.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clipboard me-2"></i> New Asset Request
            </div>
            <div class="card-body text-white">
                <form action="{{ route('asset-requests.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('asset_name') is-invalid @enderror"
                               id="asset_name" name="asset_name"
                               value="{{ old('asset_name') }}"
                               placeholder="e.g., Dell Laptop, Office Chair, Printer"
                               required>
                        @error('asset_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                   id="quantity" name="quantity"
                                   value="{{ old('quantity', 1) }}"
                                   min="1" max="9999" required>
                            @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror"
                                    id="priority" name="priority" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason / Justification <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="4"
                                  placeholder="Explain why this asset is needed..."
                                  required>{{ old('reason') }}</textarea>
                        @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Additional Notes <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="2"
                                  placeholder="Any additional information...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('asset-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
