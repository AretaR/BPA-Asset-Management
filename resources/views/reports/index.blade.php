@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fas fa-chart-bar me-2"></i> Reports
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-building text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Assets by Department</h5>
                        <p class="text-muted mb-0">View asset distribution across departments</p>
                    </div>
                </div>
                <a href="{{ route('reports.assets_by_department') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-chart-pie text-success fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Assets by Status</h5>
                        <p class="text-muted mb-0">View asset count by current status</p>
                    </div>
                </div>
                <a href="{{ route('reports.assets_by_status') }}" class="btn btn-success">
                    <i class="fas fa-eye me-2"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-dollar-sign text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Asset Value Summary</h5>
                        <p class="text-muted mb-0">View total value of assets</p>
                    </div>
                </div>
                <a href="{{ route('reports.assets_value') }}" class="btn btn-warning">
                    <i class="fas fa-eye me-2"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-history text-info fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Activity Logs</h5>
                        <p class="text-muted mb-0">View system activity and audit trail</p>
                    </div>
                </div>
                <a href="{{ route('reports.activity_logs') }}" class="btn btn-info">
                    <i class="fas fa-eye me-2"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection