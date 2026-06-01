@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <div>
            <h1><i class="fas fa-tachometer-alt me-2"></i> Dashboard Overview</h1>
            <p>Welcome back, {{ auth()->user()->name }}. Here's what's happening with your assets.</p>
        </div>
        <span class="header-date">
            <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('l, F j, Y') }}
        </span>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="{{ route('assets.index') }}" class="text-decoration-none">
            <div class="card stat-card stat-blue h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Assets</div>
                            <h2 class="mb-0">{{ $stats['total_assets'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="fw-semibold" style="color: var(--primary);">View Inventory <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="{{ route('assets.index', ['status' => 'available']) }}" class="text-decoration-none">
            <div class="card stat-card stat-green h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Available</div>
                            <h2 class="mb-0">{{ $stats['available_assets'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="fw-semibold" style="color: #059669;">View Available <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="{{ route('assets.index', ['status' => 'assigned']) }}" class="text-decoration-none">
            <div class="card stat-card stat-cyan h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Assigned</div>
                            <h2 class="mb-0">{{ $stats['assigned_assets'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="fw-semibold" style="color: #0284C7;">View Assigned <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="{{ route('assets.index', ['status' => 'maintenance']) }}" class="text-decoration-none">
            <div class="card stat-card stat-amber h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">In Maintenance</div>
                            <h2 class="mb-0">{{ $stats['maintenance_assets'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-wrench"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="fw-semibold" style="color: #D97706;">View Maintenance <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-4">
        <div class="card h-100">
            <div class="card-body metric-card">
                <div class="metric-icon" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h5>Total Portfolio Value</h5>
                <h2 class="mb-0">${{ number_format($stats['total_value'], 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card h-100">
            <div class="card-body metric-card">
                <div class="metric-icon" style="background: rgba(148, 163, 184, 0.12); color: #94a3b8;">
                    <i class="fas fa-archive"></i>
                </div>
                <h5>Retired Assets</h5>
                <h2 class="mb-0">{{ $stats['retired_assets'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card h-100">
            <div class="card-body metric-card">
                <div class="metric-icon" style="background: rgba(52, 211, 153, 0.12); color: #34d399;">
                    <i class="fas fa-users"></i>
                </div>
                <h5>Total System Users</h5>
                <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
            </div>
        </div>
    </div>
</div>

@if($assetsByStatus->isNotEmpty())
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <span class="section-title"><i class="fas fa-chart-pie"></i> Asset Status Distribution</span>
            </div>
            <div class="card-body">
                @php
                    $total = $assetsByStatus->sum();
                    $statusColorMap = [
                        'available' => 'bg-success',
                        'assigned' => 'bg-primary',
                        'maintenance' => 'bg-warning',
                        'retired' => 'bg-secondary',
                    ];
                @endphp
                <div class="status-bar">
                    @foreach($assetsByStatus as $status => $count)
                        @if($count > 0)
                            <div class="status-bar-segment {{ $statusColorMap[$status] ?? 'bg-secondary' }}" style="width: {{ ($count / $total) * 100 }}%"></div>
                        @endif
                    @endforeach
                </div>
                <div class="status-legend">
                    @foreach($assetsByStatus as $status => $count)
                        @if($count > 0)
                            <div class="status-legend-item">
                                <span class="status-legend-dot {{ $statusColorMap[$status] ?? 'bg-secondary' }}"></span>
                                {{ ucfirst($status) }} ({{ $count }})
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <span class="section-title"><i class="fas fa-boxes"></i> Assets by Status</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assetsByStatus as $status => $count)
                            @php $badgeColor = str_replace('bg-', '', $statusColorMap[$status] ?? 'bg-secondary'); @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $badgeColor }} px-3 py-2">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $count }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; max-width: 120px;">
                                            <div class="progress-bar {{ $statusColorMap[$status] ?? 'bg-secondary' }}" style="width: {{ ($count / $total) * 100 }}%"></div>
                                        </div>
                                        <span class="text-muted small">{{ number_format(($count / $total) * 100, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <span class="section-title"><i class="fas fa-clock"></i> Recently Added Assets</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Asset Tag</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAssets as $asset)
                            <tr>
                                <td>
                                    <a href="{{ route('assets.show', $asset) }}" class="fw-semibold text-decoration-none" style="color: var(--primary);">
                                        {{ $asset->asset_tag }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color: var(--text-primary);">{{ $asset->name }}</div>
                                    @if($asset->category)
                                        <small class="text-muted">{{ $asset->category->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $asset->status_badge_class }} px-3 py-2">
                                        {{ ucfirst($asset->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--border);"></i>
                                    <p class="mb-0 text-muted">No recent assets found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <span class="section-title"><i class="fas fa-history"></i> Activity Log</span>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    @forelse($stats['recent_activities'] as $activity)
                    <div class="timeline-item">
                            <div class="timeline-icon" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8;">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="timeline-content border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong style="color: var(--text-primary);">{{ $activity->user->name }}</strong>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-muted small">
                                    {{ ucfirst($activity->action) }}
                                    @if($activity->model_id)
                                        @php
                                            $modelClass = class_basename($activity->model_type);
                                        @endphp
                                        <span class="fw-semibold" style="color: var(--text-primary);">{{ $modelClass }} #{{ $activity->model_id }}</span>
                                    @endif
                                </p>
                            </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-clipboard-list fa-3x mb-3" style="color: var(--border);"></i>
                        <p class="mb-0 text-muted">No recent activity recorded.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
