@php
    $totalRecords = $assets->count();
    $filterInfo = request()->has('status') ? 'Status: ' . ucfirst(request('status')) : 'All statuses';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assets List - {{ \App\Models\Setting::companyName() }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 14mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Inter', sans-serif;
            font-size: 8px;
            color: #1e293b;
            line-height: 1.35;
            width: 100%;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none !important;
        }

        .preview-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }

        .preview-bar .bar-title {
            color: #f8fafc;
            font-size: 13px;
            font-weight: 600;
        }

        .preview-bar .bar-title i {
            color: #38bdf8;
            margin-right: 8px;
        }

        .preview-bar .bar-actions {
            display: flex;
            gap: 6px;
        }

        .preview-bar .bar-actions a,
        .preview-bar .bar-actions button {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }

        .preview-bar .btn-print {
            background: #38bdf8;
            color: #00354a;
            border-color: #38bdf8;
        }

        .preview-bar .btn-pdf {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        .preview-bar .btn-close {
            background: #334155;
            color: #f8fafc;
            border-color: #475569;
        }

        .has-preview-bar {
            margin-top: 50px;
        }

        .report-title span {
            font-size: 6.5px;
            color: #64748b;
        }

        .header {
            border-bottom: 2px solid #1e293b;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header-inner {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .org-name {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }

        .org-tagline {
            font-size: 6.5px;
            color: #64748b;
        }

        .report-title {
            text-align: right;
        }

        .report-title h1 {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.2px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 4px 8px;
            margin-bottom: 5px;
            font-size: 6.5px;
        }

        .meta-item {
            display: inline-block;
        }

        .meta-label {
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            font-size: 5.5px;
            letter-spacing: 0.3px;
        }

        .meta-value {
            color: #0f172a;
            font-weight: 500;
        }

        .records-count {
            font-size: 6.5px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 7px;
        }

        thead th {
            background: #1e293b;
            color: #f8fafc;
            font-weight: 600;
            font-size: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 4px 5px;
            text-align: left;
            border: 1px solid #334155;
        }

        thead th.text-end {
            text-align: right;
        }

        tbody td {
            padding: 2.5px 5px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tfoot td {
            padding: 3px 5px;
            border: 1px solid #e2e8f0;
            background: #e2e8f0;
            font-weight: 700;
            font-size: 7px;
        }

        tfoot td.text-end {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 100px;
            font-size: 5.5px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .badge.bg-success { background: #dcfce7; color: #166534; }
        .badge.bg-primary { background: #dbeafe; color: #1e40af; }
        .badge.bg-warning { background: #fef3c7; color: #92400e; }
        .badge.bg-danger  { background: #fee2e2; color: #991b1b; }
        .badge.bg-secondary { background: #f1f5f9; color: #475569; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
            display: flex;
            justify-content: space-between;
            font-size: 6px;
            color: #94a3b8;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .preview-bar {
                display: none !important;
            }

            .has-preview-bar {
                margin-top: 0;
            }

            thead {
                display: table-header-group;
            }

            tbody tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    @if(request()->has('preview'))
    <div class="preview-bar no-print">
        <div class="bar-title">
            <i class="fas fa-file-alt"></i> Print Preview
            <span style="font-weight:400;font-size:11px;color:#94a3b8;margin-left:6px">Assets List</span>
        </div>
        <div class="bar-actions">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('assets.export', ['format' => 'pdf'] + request()->query()) }}" class="btn-pdf">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('assets.index') }}" class="btn-close">
                <i class="fas fa-times"></i> Close
            </a>
        </div>
    </div>
    @endif

    <div class="{{ request()->has('preview') ? 'has-preview-bar' : '' }}">

        <div class="header">
            <div class="header-inner">
                <div>
                    <div class="org-name">{{ \App\Models\Setting::companyName() }}</div>
                    <div class="org-tagline">Asset Management System</div>
                </div>
                <div class="report-title">
                    <h1>Assets List</h1>
                    <span>Complete list of all registered assets</span>
                </div>
            </div>
        </div>

        <div class="meta">
            <div class="meta-item">
                <div class="meta-label">Generated</div>
                <div class="meta-value">{{ now()->format('M d, Y h:i A') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Prepared By</div>
                <div class="meta-value">{{ auth()->user()->name ?? 'System' }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Records</div>
                <div class="meta-value">{{ $totalRecords }}</div>
            </div>
            @if(isset($filterInfo) && $filterInfo)
            <div class="meta-item">
                <div class="meta-label">Filters</div>
                <div class="meta-value">{{ $filterInfo }}</div>
            </div>
            @endif
        </div>

        <div class="records-count">Showing {{ $totalRecords }} asset{{ $totalRecords !== 1 ? 's' : '' }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width:13%">Asset Tag</th>
                    <th style="width:22%">Name</th>
                    <th style="width:12%">Category</th>
                    <th style="width:11%">Department</th>
                    <th style="width:13%">Assigned To</th>
                    <th style="width:9%">Status</th>
                    <th style="width:11%">Location</th>
                    <th style="width:9%" class="text-end">Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->asset_tag }}</td>
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

        <div class="footer">
            <span>{{ \App\Models\Setting::companyName() }}</span>
            <span>{{ now()->format('M d, Y') }}</span>
            <span>Page 1</span>
        </div>

    </div>

</body>
</html>
