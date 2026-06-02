@extends('reports.print-layout')

@php
    $title = 'Assets List';
    $description = 'Complete list of all registered assets';
    $totalRecords = $assets->count();
    $filterInfo = request()->has('status') ? 'Status: ' . ucfirst(request('status')) : 'All statuses';
    $backUrl = route('assets.index');
    $pdfUrl = request()->has('preview') ? route('assets.export', ['format' => 'pdf'] + request()->query()) : null;
@endphp

@push('styles')
<style>
    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    .no-print,
    .preview-controls {
        display: none !important;
    }

    body {
        font-family: 'DejaVu Sans', 'Inter', sans-serif;
        font-size: 9px;
    }

    .report-page {
        width: auto;
        min-height: auto;
        margin: 0;
        padding: 0;
        box-shadow: none;
    }

    .report-header {
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .report-header .brand-logo {
        width: 36px;
        height: 36px;
        font-size: 18px;
    }

    .report-header .brand-logo img {
        width: 36px;
        height: 36px;
    }

    .report-header .brand-info .org-name {
        font-size: 13px;
    }

    .report-header .brand-info .org-tagline {
        font-size: 8px;
    }

    .report-header .report-badge .report-title {
        font-size: 15px;
    }

    .report-header .report-badge .report-subtitle {
        font-size: 8px;
    }

    .report-meta {
        padding: 6px 10px;
        margin-bottom: 10px;
        font-size: 8px;
    }

    .records-count {
        font-size: 8px;
        margin-bottom: 6px;
    }

    .report-table {
        font-size: 7.5px;
        margin-bottom: 8px;
    }

    .report-table thead th {
        font-size: 7px;
        padding: 5px 5px;
    }

    .report-table tbody td {
        padding: 3px 5px;
    }

    .report-table tfoot td {
        padding: 5px;
        font-size: 8px;
    }

    .report-table .badge {
        font-size: 6.5px;
        padding: 1px 4px;
    }

    .report-footer {
        display: none;
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
                <th class="text-end">Purchase Cost</th>
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
                <td><span class="badge {{ $asset->status_badge_class }}">{{ ucfirst($asset->status) }}</span></td>
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
                <td colspan="7">Total</td>
                <td class="text-end">${{ number_format($assets->sum('purchase_cost'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
