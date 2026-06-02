@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-dollar-sign me-2"></i> Asset Value Summary
    </h1>
    <div class="btn-group">
        <a href="{{ route('reports.assets_value', ['preview' => 1] + request()->query()) }}" class="btn btn-info" target="_blank">
            <i class="fas fa-eye me-2"></i> Preview
        </a>
        <a href="{{ route('reports.assets_value', ['preview' => 1] + request()->query()) }}" class="btn btn-primary" target="_blank" onclick="event.preventDefault(); window.open(this.href, 'print-preview', 'width=1200,height=800'); return false;">
            <i class="fas fa-print me-2"></i> Print
        </a>
        <a href="{{ route('reports.assets_value', ['format' => 'pdf'] + request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i> Export PDF
        </a>
        <a href="{{ route('reports.assets_value', ['format' => 'xlsx'] + request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel me-2"></i> Excel
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Assets</h5>
                <h2 class="mb-0">{{ $totalAssets }}</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Value</h5>
                <h2 class="mb-0">${{ number_format($totalValue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Active Value</h5>
                <h2 class="mb-0">${{ number_format($activeValue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Retired Value</h5>
                <h2 class="mb-0">${{ number_format($retiredValue, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-tags me-2"></i> Value by Category
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Assets Count</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryValues as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><span class="badge bg-info">{{ $category->assets_count }}</span></td>
                        <td>${{ number_format($category->assets_sum_purchase_cost ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No categories found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection