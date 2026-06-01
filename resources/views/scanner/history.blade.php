@extends('layouts.app')

@section('title', 'Scan History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="page-header mb-0">
        <i class="fas fa-history me-2"></i> Scan History
    </h1>
    <a href="{{ route('scanner.index') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-qrcode me-1"></i> Open Scanner
    </a>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-list me-2"></i> All Scans</span>
        <small class="text-muted">{{ $logs->total() }} total records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="d-none d-md-table-cell">#</th>
                        <th>Asset</th>
                        <th>Scan Type</th>
                        <th>Scanned By</th>
                        <th class="d-none d-md-table-cell">Device</th>
                        <th class="d-none d-md-table-cell">IP Address</th>
                        <th>Scanned At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-muted d-none d-md-table-cell">{{ $log->id }}</td>
                        <td>
                            @if($log->asset)
                                <div class="fw-semibold">{{ $log->asset->name }}</div>
                                <small class="text-muted">{{ $log->asset->asset_tag }}</small>
                            @else
                                <span class="text-muted fst-italic">Deleted asset</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $log->scan_type_badge }}">
                                <i class="{{ $log->scan_type_icon }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $log->scan_type)) }}
                            </span>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                         style="width:28px;height:28px;font-size:.7rem;flex-shrink:0;">
                                        {{ substr($log->user->name, 0, 1) }}
                                    </div>
                                    <small>{{ $log->user->name }}</small>
                                </div>
                            @else
                                <small class="text-muted">Public / Guest</small>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            <small class="text-muted text-truncate d-block" style="max-width:140px;" title="{{ $log->device }}">
                                {{ Str::limit($log->device, 35) }}
                            </small>
                        </td>
                        <td class="d-none d-md-table-cell"><small class="text-muted">{{ $log->ip_address }}</small></td>
                        <td>
                            <div>{{ $log->scanned_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $log->scanned_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            @if($log->asset)
                                <a href="{{ route('assets.show', $log->asset->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-qrcode fa-3x mb-3 d-block opacity-25"></i>
                            No scan records yet. Start scanning assets!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
