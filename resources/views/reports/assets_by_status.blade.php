@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-chart-pie me-2"></i> Assets by Status
    </h1>
    <div class="btn-group">
        <a href="{{ route('reports.assets_by_status', ['preview' => 1] + request()->query()) }}" class="btn btn-info" target="_blank">
            <i class="fas fa-eye me-2"></i> Preview
        </a>
        <a href="{{ route('reports.assets_by_status', ['preview' => 1] + request()->query()) }}" class="btn btn-primary" target="_blank" onclick="event.preventDefault(); window.open(this.href, 'print-preview', 'width=1200,height=800'); return false;">
            <i class="fas fa-print me-2"></i> Print
        </a>
        <a href="{{ route('reports.assets_by_status', ['format' => 'pdf'] + request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i> Export PDF
        </a>
        <a href="{{ route('reports.assets_by_status', ['format' => 'xlsx'] + request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel me-2"></i> Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> Status Summary
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Total Value</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusData as $status => $data)
                    @php
                        $totalCount = array_sum(array_column($statusData, 'count'));
                        $percentage = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
                        $badgeClass = match($status) {
                            'available' => 'bg-success',
                            'assigned' => 'bg-primary',
                            'maintenance' => 'bg-warning',
                            'retired' => 'bg-secondary',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <tr>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td><strong>{{ $data['count'] }}</strong></td>
                        <td>${{ number_format($data['total_value'], 2) }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $badgeClass }}" role="progressbar" 
                                     style="width: {{ $percentage }}%">{{ $percentage }}%</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection