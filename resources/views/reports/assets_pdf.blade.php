@extends('reports.print-layout')

@php
    $totalRecords = $assets->count();
    $title = 'Assets List';
    $description = 'Complete list of all registered assets';
    $filterInfo = request()->has('status') ? 'Status: ' . ucfirst(request('status')) : 'All statuses';
    $backUrl = route('assets.index');
    $pdfUrl = request()->has('preview') ? route('assets.export', ['format' => 'pdf'] + request()->query()) : null;
@endphp

@push('styles')
<style>
    .report-table {
        font-size: 8px;
    }

    .report-table thead th {
        font-size: 7px;
        padding: 5px 6px;
    }

    .report-table tbody td {
        padding: 4px 6px;
    }

    .report-table tfoot td {
        padding: 5px 6px;
        font-size: 8px;
    }

    .report-table .badge {
        font-size: 7px;
        padding: 1px 5px;
    }
</style>
@endpush

@section('report-content')
    <div class="records-count">Showing {{ $totalRecords }} asset{{ $totalRecords !== 1 ? 's' : '' }}</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Asset Tag</th>
                <th>Name</th>
                <th>Category</th>
                <th>Department</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Location</th>
                <th class="text-end">Cost</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
            <tr>
                <td><strong>{{ $asset->asset_tag }}</strong></td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                <td>{{ $asset->department->name ?? 'N/A' }}</td>
                <td>{{ $asset->assignedUser->name ?? 'Unassigned' }}</td>
                <td><span class="badge bg-{{ $asset->status === 'available' ? 'success' : ($asset->status === 'assigned' ? 'primary' : ($asset->status === 'maintenance' ? 'warning' : 'secondary')) }}">{{ ucfirst($asset->status) }}</span></td>
                <td>{{ $asset->location ?? 'N/A' }}</td>
                <td class="text-end">${{ number_format((float) ($asset->purchase_cost ?? 0), 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#94a3b8;padding:24px;">No assets found</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total ({{ $totalRecords }} asset{{ $totalRecords !== 1 ? 's' : '' }})</td>
                <td class="text-end">${{ number_format($assets->sum('purchase_cost'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
