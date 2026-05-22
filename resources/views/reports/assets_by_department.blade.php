@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-building me-2"></i> Assets by Department
    </h1>
    <div class="btn-group">
        <a href="{{ route('reports.assets_by_department', ['format' => 'xlsx']) }}" class="btn btn-success">
            <i class="fas fa-file-excel me-2"></i> Excel
        </a>
        <a href="{{ route('reports.assets_by_department', ['format' => 'pdf']) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i> PDF
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> Department Summary
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Code</th>
                        <th>Location</th>
                        <th>Assets Count</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td><strong>{{ $department->name }}</strong></td>
                        <td>{{ $department->code }}</td>
                        <td>{{ $department->location ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary">{{ $department->assets_count }}</span></td>
                        <td>${{ number_format($department->assets_sum_purchase_cost ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No departments found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3">Total</td>
                        <td><span class="badge bg-dark">{{ $departments->sum('assets_count') }}</span></td>
                        <td>${{ number_format($departments->sum('assets_sum_purchase_cost') ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection