@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-boxes me-2"></i> Assets
    </h1>
        <div>
        <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#scanModal" title="Scan Barcode">
            <i class="fas fa-camera me-1"></i> Scan
        </button>
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
        <div class="btn-group">
            <a href="{{ route('assets.export', array_merge(request()->all(), ['format' => 'xlsx'])) }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>
            <a href="{{ route('assets.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Asset Tag</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Department</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Purchase Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td class="text-center">
                            <img src="{{ route('assets.barcode', $asset->serial_number ?? $asset->asset_tag) }}" alt="Barcode" style="height: 30px; width: auto;">
                            <br><small class="text-muted" style="font-size: 0.65rem;">{{ $asset->serial_number ?? $asset->asset_tag }}</small>
                        </td>
                        <td>
                            <a href="{{ route('assets.show', $asset) }}">
                                <strong>{{ $asset->asset_tag }}</strong>
                            </a>
                        </td>
                        <td>{{ Str::limit($asset->name, 30) }}</td>
                        <td>{{ $asset->category->name ?? 'N/A' }}</td>
                        <td>{{ $asset->department->name ?? 'N/A' }}</td>
                        <td>{{ $asset->assignedUser->name ?? 'Unassigned' }}</td>
                        <td>
                            <span class="badge {{ $asset->status_badge_class }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </td>
                        <td>${{ number_format($asset->purchase_cost, 2) }}</td>
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
<!-- Scan Barcode Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-camera me-2 text-primary"></i>Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="scanner-reader" style="width: 100%; min-height: 300px;"></div>
                <div id="scan-result" class="mt-3 d-none">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        <span>Processing...</span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">Point your camera at a barcode to scan</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="stopScanner()">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;
let scannerRunning = false;

function startScanner() {
    const reader = document.getElementById('scanner-reader');
    reader.innerHTML = '';

    html5QrCode = new Html5Qrcode('scanner-reader');

    html5QrCode.start(
        { facingMode: 'environment' },
        {
            fps: 10,
            qrbox: { width: 250, height: 100 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.QR_CODE,
            ],
        },
        onScanSuccess,
        onScanFailure,
    ).catch(err => {
        reader.innerHTML = `
            <div class="alert alert-danger text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <p class="mb-0">Camera access denied or unavailable.</p>
                <small>Please grant camera permission or use a different device.</small>
            </div>`;
    });
}

function onScanSuccess(decodedText, decodedResult) {
    if (scannerRunning) return;
    scannerRunning = true;

    if (html5QrCode) {
        html5QrCode.pause();
    }

    const resultDiv = document.getElementById('scan-result');
    resultDiv.classList.remove('d-none');
    resultDiv.innerHTML = `
        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-spinner fa-spin me-2"></i>
            <span>Looking up: <strong>${decodedText}</strong>...</span>
        </div>`;

    fetch('{{ route('assets.scan') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ code: decodedText }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.found) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Found: <strong>${data.name}</strong> (${data.asset_tag})
                    <a href="${data.url}" class="btn btn-sm btn-primary ms-3">View Asset</a>
                </div>`;
            setTimeout(() => { window.location.href = data.url; }, 1500);
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exclamation-circle me-2"></i> ${data.message}</span>
                    <button class="btn btn-sm btn-outline-warning" onclick="resumeScanner()">Scan Again</button>
                </div>`;
            scannerRunning = false;
        }
    })
    .catch(err => {
        resultDiv.innerHTML = `<div class="alert alert-danger">Scan error. Please try again.</div>`;
        scannerRunning = false;
    });
}

function onScanFailure(err) {
    // ignore
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
        }).catch(() => {});
    }
    scannerRunning = false;
}

function resumeScanner() {
    scannerRunning = false;
    document.getElementById('scan-result').classList.add('d-none');
    if (html5QrCode) {
        html5QrCode.resume();
    }
}

document.getElementById('scanModal').addEventListener('shown.bs.modal', function () {
    startScanner();
});

document.getElementById('scanModal').addEventListener('hidden.bs.modal', function () {
    stopScanner();
    document.getElementById('scan-result').classList.add('d-none');
    scannerRunning = false;
});
</script>
@endpush
@endsection