@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-plus-circle me-2"></i> Add New Asset
    </h1>
    <a href="{{ route('assets.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Asset Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Asset Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                       id="serial_number" name="serial_number" value="{{ old('serial_number') }}" placeholder="Scan or type serial number">
                                <button class="btn btn-outline-primary" type="button" title="Scan barcode" data-bs-toggle="modal" data-bs-target="#scanSerialModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select @error('department_id') is-invalid @enderror" 
                                    id="department_id" name="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="assigned_to" class="form-label">Assign To</label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                    id="assigned_to" name="assigned_to">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="purchase_cost" class="form-label">Purchase Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('purchase_cost') is-invalid @enderror" 
                                       id="purchase_cost" name="purchase_cost" value="{{ old('purchase_cost') }}">
                            </div>
                            @error('purchase_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                            <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                   id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                            @error('warranty_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="manufacturer" class="form-label">Manufacturer</label>
                            <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" 
                                   id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
                            @error('manufacturer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model') }}">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-image me-2"></i> Asset Image
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('assets.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> Create Asset
        </button>
    </div>
</form>

<!-- Scan Serial Number Modal -->
<div class="modal fade" id="scanSerialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fas fa-camera me-2 text-primary"></i>Scan Serial Number</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="serial-scanner" style="width: 100%; min-height: 200px;"></div>
                <div id="serial-scan-result" class="mt-2 d-none">
                    <div class="alert alert-success py-2 px-3 mb-0">
                        <i class="fas fa-check-circle me-1"></i> <span id="serial-scanned-value"></span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">Point camera at a barcode</small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('imagePreview');
        output.src = reader.result;
        output.classList.remove('d-none');
    }
    reader.readAsDataURL(event.target.files[0]);
}

let serialScanner = null;
let serialScanning = false;

document.getElementById('scanSerialModal').addEventListener('shown.bs.modal', function () {
    const container = document.getElementById('serial-scanner');
    container.innerHTML = '';

    setTimeout(() => {
        serialScanner = new Html5Qrcode('serial-scanner');
    serialScanner.start(
        { facingMode: 'environment' },
        {
            fps: 10,
            qrbox: { width: 200, height: 80 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
            ],
        },
        function onScan(decodedText) {
            if (serialScanning) return;
            serialScanning = true;

            serialScanner.pause();
            document.getElementById('serial_number').value = decodedText;
            document.getElementById('serial-scanned-value').textContent = decodedText;
            document.getElementById('serial-scan-result').classList.remove('d-none');

            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('scanSerialModal'));
                if (modal) modal.hide();
            }, 600);
        },
            function onFail() {}
        ).catch(() => {
            container.innerHTML = '<div class="alert alert-danger text-center py-4 mb-0"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br><small>Camera unavailable</small></div>';
        });
    }, 500);
});

document.getElementById('scanSerialModal').addEventListener('hidden.bs.modal', function () {
    if (serialScanner) {
        serialScanner.stop().then(() => serialScanner.clear()).catch(() => {});
    }
    serialScanning = false;
    document.getElementById('serial-scan-result').classList.add('d-none');
});
</script>
@endpush
@endsection