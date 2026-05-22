@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-box me-2"></i> {{ $asset->name }}
    </h1>
    <div>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @can('update', $asset)
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i> Edit
        </a>
        @endcan
        @can('delete', $asset)
        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                <i class="fas fa-trash me-2"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-barcode me-2"></i> Barcode
            </div>
            <div class="card-body text-center">
                <img src="{{ route('assets.barcode', $asset->serial_number ?? $asset->asset_tag) }}" alt="Barcode" class="img-fluid" style="max-height: 80px;">
                <p class="text-muted small mt-2 mb-0">{{ $asset->serial_number ?? $asset->asset_tag }}</p>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="printBarcode()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
        @if($asset->image)
        <div class="card">
            <div class="card-header">
                <i class="fas fa-image me-2"></i> Asset Image
            </div>
            <div class="card-body text-center">
                <img src="{{ Storage::url($asset->image) }}" alt="{{ $asset->name }}" class="img-fluid rounded">
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Asset Details
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Asset Tag</label>
                        <div class="fw-bold">{{ $asset->asset_tag }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status</label>
                        <div><span class="badge {{ $asset->status_badge_class }}">{{ ucfirst($asset->status) }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Category</label>
                        <div>{{ $asset->category->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Department</label>
                        <div>{{ $asset->department->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Assigned To</label>
                        <div>{{ $asset->assignedUser->name ?? 'Unassigned' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Location</label>
                        <div>{{ $asset->location ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Serial Number</label>
                        <div>{{ $asset->serial_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Manufacturer</label>
                        <div>{{ $asset->manufacturer ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Model</label>
                        <div>{{ $asset->model ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Purchase Date</label>
                        <div>{{ $asset->purchase_date?->format('M d, Y') ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Purchase Cost</label>
                        <div>${{ number_format($asset->purchase_cost, 2) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Warranty Expiry</label>
                        <div>{{ $asset->warranty_expiry?->format('M d, Y') ?? 'N/A' }}</div>
                    </div>
                </div>

                @if($asset->description)
                <hr>
                <div class="mb-0">
                    <label class="text-muted small">Description</label>
                    <div>{{ $asset->description }}</div>
                </div>
                @endif

                @if($asset->notes)
                <hr>
                <div class="mb-0">
                    <label class="text-muted small">Notes</label>
                    <div>{{ $asset->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($asset->status === 'available' && auth()->user()->canAssignAssets())
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user-check me-2"></i> Check Out Asset
            </div>
            <div class="card-body">
                <form action="{{ route('assets.checkout', $asset) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="assigned_to" class="form-label">Assign To *</label>
                            <select class="form-select" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i> Check Out
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($asset->status === 'assigned' && auth()->user()->canAssignAssets())
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-undo me-2"></i> Check In Asset
            </div>
            <div class="card-body">
                <form action="{{ route('assets.checkin', $asset) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo me-2"></i> Check In
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i> Movement History
            </div>
            <div class="card-body">
                @if($asset->movements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asset->movements as $movement)
                            <tr>
                                <td>{{ $movement->movement_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="{{ $movement->movement_color }}">
                                        <i class="fas fa-{{ $movement->movement_icon }}"></i>
                                        {{ ucfirst($movement->movement_type) }}
                                    </span>
                                </td>
                                <td>{{ $movement->fromDepartment->name ?? 'N/A' }}</td>
                                <td>{{ $movement->toDepartment->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($movement->notes, 30) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No movement history</p>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function printBarcode() {
    const barcodeValue = '{{ $asset->serial_number ?? $asset->asset_tag }}';
    const barcodeUrl = '{{ route('assets.barcode', $asset->serial_number ?? $asset->asset_tag) }}';
    const tag = '{{ $asset->asset_tag }}';
    const name = '{{ $asset->name }}';

    const win = window.open('', '_blank', 'width=400,height=300');
    win.document.write(`
        <html>
        <head><title>Print Barcode - ${tag}</title>
        <style>
            body { text-align: center; padding: 20px; font-family: Arial, sans-serif; }
            img { max-width: 100%; height: auto; }
            h4 { margin: 10px 0 5px; font-size: 14px; }
            p { margin: 0; font-size: 12px; color: #666; }
            @media print {
                body { padding: 0; margin: 0; }
                button { display: none; }
            }
        </style>
        </head>
        <body>
            <img src="${barcodeUrl}" alt="Barcode">
            <h4>${barcodeValue}</h4>
            <p>${name} | ${tag}</p>
            <br>
            <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">Print</button>
            <script>setTimeout(() => window.print(), 500);<\\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endpush
@endsection