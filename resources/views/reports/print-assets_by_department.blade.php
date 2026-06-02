@extends('reports.print-layout')

@php
    $title = 'Assets by Department';
    $description = 'Distribution of assets across departments';
    $totalRecords = $departments->count();
    $filterInfo = request()->has('status') ? 'Status: ' . ucfirst(request('status')) : 'All statuses';
    $backUrl = route('reports.assets_by_department');
    $pdfUrl = route('reports.assets_by_department', ['format' => 'pdf'] + request()->query());
@endphp

@section('report-content')
    <div class="records-count">Showing {{ $totalRecords }} department{{ $totalRecords !== 1 ? 's' : '' }}</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Department</th>
                <th>Code</th>
                <th>Location</th>
                <th class="text-center">Assets Count</th>
                <th class="text-end">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr>
                <td><strong>{{ $department->name }}</strong></td>
                <td>{{ $department->code }}</td>
                <td>{{ $department->location ?? 'N/A' }}</td>
                <td class="text-center">{{ $department->assets_count }}</td>
                <td class="text-end">${{ number_format($department->assets_sum_purchase_cost ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#94a3b8;padding:24px;">No departments found</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="text-center">{{ $departments->sum('assets_count') }}</td>
                <td class="text-end">${{ number_format($departments->sum('assets_sum_purchase_cost') ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
