@extends('layouts.app')

@section('title', $asset->name . ' — Asset Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="page-header mb-0">
        <i class="fas fa-box me-2"></i> {{ $asset->name }}
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        @can('update', $asset)
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        @endcan
        @can('delete', $asset)
        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete this asset?')">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="row g-4">
    {{-- ── Left sidebar ───────────────────────────────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Asset image --}}
        @if($asset->image)
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-image me-2"></i> Asset Image</div>
            <div class="card-body p-2">
                <img src="{{ Storage::url($asset->image) }}" alt="{{ $asset->name }}"
                     class="img-fluid rounded w-100" style="max-height:220px;object-fit:cover;">
            </div>
        </div>
        @endif

        {{-- ── QR Code Card ─────────────────────────────────────────────── --}}
        @if($asset->qr_uuid)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-qrcode me-2"></i> QR Code</span>
                @can('update', $asset)
                <form method="POST" action="{{ route('qrcodes.regenerate', $asset) }}" class="d-inline"
                      onsubmit="return confirm('This will invalidate existing QR codes. Continue?')">
                    @csrf
                    <button class="btn btn-xs btn-outline-secondary btn-sm py-0 px-2" title="Regenerate QR">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </form>
                @endcan
            </div>
            <div class="card-body text-center">
                <img src="{{ route('qrcodes.image', $asset) }}" alt="QR Code"
                     class="rounded" style="width:140px;height:140px;">
                <p class="text-muted small mt-2 mb-3">Scan to view asset info</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('qrcodes.print', $asset) }}" target="_blank"
                       class="btn btn-sm btn-outline-info">
                        <i class="fas fa-print me-1"></i> Print
                    </a>
                    <a href="{{ route('qrcodes.download', $asset) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> PNG
                    </a>
                    <a href="{{ route('assets.qr-public', $asset->qr_uuid) }}" target="_blank"
                       class="btn btn-sm btn-outline-success">
                        <i class="fas fa-external-link-alt me-1"></i> View
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ── Right: Asset Details ──────────────────────────────────────────── --}}
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
                        <label class="text-muted small">Serial Number</label>
                        <div>{{ $asset->serial_number ?? 'N/A' }}</div>
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
            <div class="card-header"><i class="fas fa-user-check me-2"></i> Check Out Asset</div>
            <div class="card-body">
                <form action="{{ route('assets.checkout', $asset) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign To *</label>
                            <select class="form-select" name="assigned_to" required>
                                <option value="">Select User</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Check Out
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
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-undo me-1"></i> Check In
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Movement History --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-history me-2"></i> Movement History</div>
            <div class="card-body">
                @if($asset->movements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Date</th><th>Type</th><th>From</th><th>To</th><th>Notes</th></tr></thead>
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

@endsection