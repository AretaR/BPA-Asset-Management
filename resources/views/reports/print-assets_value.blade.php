@extends('reports.print-layout')

@php
    $title = 'Asset Value Summary';
    $description = 'Summary of total asset values by category';
    $totalRecords = $categoryValues->count();
    $filterInfo = 'All categories';
    $backUrl = route('reports.assets_value');
    $pdfUrl = route('reports.assets_value', ['format' => 'pdf'] + request()->query());
@endphp

@section('report-content')
    {{-- Summary Cards --}}
    <div class="summary-cards">
        <div class="summary-card bg-primary">
            <div class="card-label">Total Assets</div>
            <div class="card-value text-primary">{{ $totalAssets }}</div>
        </div>
        <div class="summary-card bg-success">
            <div class="card-label">Total Value</div>
            <div class="card-value text-success">${{ number_format($totalValue, 2) }}</div>
        </div>
        <div class="summary-card bg-info">
            <div class="card-label">Active Value</div>
            <div class="card-value text-info">${{ number_format($activeValue, 2) }}</div>
        </div>
        <div class="summary-card bg-secondary">
            <div class="card-label">Retired Value</div>
            <div class="card-value text-secondary">${{ number_format($retiredValue, 2) }}</div>
        </div>
    </div>

    <div class="records-count">Showing {{ $totalRecords }} categor{{ $totalRecords !== 1 ? 'ies' : 'y' }}</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Category</th>
                <th class="text-center">Assets Count</th>
                <th class="text-end">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categoryValues as $category)
            <tr>
                <td><strong>{{ $category->name }}</strong></td>
                <td class="text-center">{{ $category->assets_count }}</td>
                <td class="text-end">${{ number_format($category->assets_sum_purchase_cost ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center;color:#94a3b8;padding:24px;">No categories found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
