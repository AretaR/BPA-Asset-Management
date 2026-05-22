@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0 text-dark fw-bold">
        <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard Overview
    </h1>
    <div>
        <span class="text-muted"><i class="fas fa-calendar-alt me-1"></i> {{ now()->format('l, F j, Y') }}</span>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Total Assets -->
    <div class="col-xl-3 col-lg-6">
        <a href="{{ route('assets.index') }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1 small">Total Assets</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['total_assets'] }}</h2>
                        </div>
                        <div class="icon-box bg-primary-soft text-primary">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <small class="text-primary fw-semibold">View Inventory <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <!-- Available Assets -->
    <div class="col-xl-3 col-lg-6">
        <a href="{{ route('assets.index', ['status' => 'available']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1 small">Available</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['available_assets'] }}</h2>
                        </div>
                        <div class="icon-box bg-success-soft text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <small class="text-success fw-semibold">View Available <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <!-- Assigned Assets -->
    <div class="col-xl-3 col-lg-6">
        <a href="{{ route('assets.index', ['status' => 'assigned']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1 small">Assigned</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['assigned_assets'] }}</h2>
                        </div>
                        <div class="icon-box bg-info-soft text-info">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <small class="text-info fw-semibold">View Assigned <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>

    <!-- In Maintenance -->
    <div class="col-xl-3 col-lg-6">
        <a href="{{ route('assets.index', ['status' => 'maintenance']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1 small">In Maintenance</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['maintenance_assets'] }}</h2>
                        </div>
                        <div class="icon-box bg-warning-soft text-warning">
                            <i class="fas fa-wrench fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <small class="text-warning fw-semibold">View Maintenance <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                <div class="mb-3">
                    <div class="icon-box-lg bg-primary-soft text-primary mx-auto">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
                <h5 class="text-muted text-uppercase fw-semibold small mb-2">Total Portfolio Value</h5>
                <h2 class="text-dark fw-bold mb-0">${{ number_format($stats['total_value'], 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                <div class="mb-3">
                    <div class="icon-box-lg bg-secondary-soft text-secondary mx-auto">
                        <i class="fas fa-archive fa-2x"></i>
                    </div>
                </div>
                <h5 class="text-muted text-uppercase fw-semibold small mb-2">Retired Assets</h5>
                <h2 class="text-dark fw-bold mb-0">{{ $stats['retired_assets'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                <div class="mb-3">
                    <div class="icon-box-lg bg-success-soft text-success mx-auto">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <h5 class="text-muted text-uppercase fw-semibold small mb-2">Total System Users</h5>
                <h2 class="text-dark fw-bold mb-0">{{ $stats['total_users'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Assets Table -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="card-title fw-bold text-dark mb-0"><i class="fas fa-clock me-2 text-primary"></i> Recently Added Assets</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="border-0 rounded-start">Asset Tag</th>
                                <th class="border-0">Name</th>
                                <th class="border-0 rounded-end">Status</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($recentAssets as $asset)
                            <tr>
                                <td>
                                    <a href="{{ route('assets.show', $asset) }}" class="fw-semibold text-primary text-decoration-none">
                                        {{ $asset->asset_tag }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $asset->name }}</div>
                                    @if($asset->category)
                                        <small class="text-muted">{{ $asset->category->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ str_replace('bg-', 'bg-soft-', $asset->status_badge_class) }} {{ str_replace('bg-', 'text-', $asset->status_badge_class) }} px-3 py-2">
                                        {{ ucfirst($asset->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">No recent assets found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Timeline -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="card-title fw-bold text-dark mb-0"><i class="fas fa-history me-2 text-primary"></i> Activity Log</h5>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    @forelse($stats['recent_activities'] as $activity)
                    <div class="timeline-item d-flex mb-3">
                        <div class="timeline-icon bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="timeline-content flex-grow-1 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark">{{ $activity->user->name }}</strong>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-muted small">
                                {{ ucfirst($activity->action) }}
                                @if($activity->model_id)
                                    @php
                                        $modelClass = class_basename($activity->model_type);
                                    @endphp
                                    <span class="fw-semibold text-dark">{{ $modelClass }} #{{ $activity->model_id }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                        <p class="mb-0">No recent activity recorded.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Specific Custom Styles */
.stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 0.75rem;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}
.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.icon-box-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Soft color utilities */
.bg-primary-soft { background-color: rgba(79, 70, 229, 0.1); }
.text-primary { color: #4f46e5 !important; }

.bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
.text-success { color: #10b981 !important; }

.bg-info-soft { background-color: rgba(14, 165, 233, 0.1); }
.text-info { color: #0ea5e9 !important; }

.bg-warning-soft { background-color: rgba(245, 158, 11, 0.1); }
.text-warning { color: #f59e0b !important; }

.bg-secondary-soft { background-color: rgba(100, 116, 139, 0.1); }
.text-secondary { color: #64748b !important; }

/* Timeline styling */
.activity-timeline {
    position: relative;
}
.timeline-item:last-child .timeline-content {
    border-bottom: none !important;
    padding-bottom: 0 !important;
}

.table th {
    font-weight: 600;
    letter-spacing: 0.5px;
}

@media (max-width: 575.98px) {
    .table th,
    .table td {
        font-size: 0.8125rem;
        padding: 8px !important;
    }
}
</style>
@endsection