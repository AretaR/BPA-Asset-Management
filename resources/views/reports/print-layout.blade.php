<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Report' }} - {{ \App\Models\Setting::companyName() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #fff;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: block;
        }

        .print-only {
            display: none;
        }

        /* ─── PREVIEW CONTROLS ─── */
        .preview-controls {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }

        .preview-controls .preview-title {
            color: #f8fafc;
            font-size: 14px;
            font-weight: 600;
        }

        .preview-controls .preview-title i {
            color: #38bdf8;
            margin-right: 8px;
        }

        .preview-controls .btn-group {
            display: flex;
            gap: 8px;
        }

        .preview-controls .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }

        .preview-controls .btn-primary {
            background: #38bdf8;
            color: #00354a;
            border-color: #38bdf8;
        }
        .preview-controls .btn-primary:hover {
            background: #7dd3fc;
        }

        .preview-controls .btn-danger {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }
        .preview-controls .btn-danger:hover {
            background: #f87171;
        }

        .preview-controls .btn-secondary {
            background: #334155;
            color: #f8fafc;
            border-color: #475569;
        }
        .preview-controls .btn-secondary:hover {
            background: #475569;
        }

        /* ─── PAGE CONTAINER ─── */
        .report-page {
            width: 210mm;
            min-height: 297mm;
            margin: 60px auto 0;
            padding: 20mm 20mm 25mm;
            background: #fff;
            position: relative;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        /* ─── REPORT HEADER ─── */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 3px solid #1e293b;
            margin-bottom: 16px;
        }

        .report-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .report-header .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: contain;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #38bdf8;
        }

        .report-header .brand-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 8px;
        }

        .report-header .brand-info .org-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .report-header .brand-info .org-tagline {
            font-size: 10px;
            color: #64748b;
        }

        .report-header .report-badge {
            text-align: right;
        }

        .report-header .report-badge .report-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .report-header .report-badge .report-subtitle {
            font-size: 10px;
            color: #64748b;
        }

        /* ─── REPORT META ─── */
        .report-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 10px;
        }

        .report-meta .meta-item {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .report-meta .meta-label {
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.4px;
        }

        .report-meta .meta-value {
            color: #0f172a;
            font-weight: 500;
        }

        /* ─── REPORT TABLE ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 12px;
        }

        .report-table thead th {
            background: #1e293b;
            color: #f8fafc;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #334155;
        }

        .report-table tbody td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .report-table tbody tr:hover {
            background: #f1f5f9;
        }

        .report-table tfoot td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
            font-weight: 700;
            font-size: 10px;
        }

        .report-table .text-end {
            text-align: right;
        }

        .report-table .text-center {
            text-align: center;
        }

        .report-table .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 100px;
            font-size: 9px;
            font-weight: 600;
            background: #e2e8f0;
            color: #475569;
        }

        .report-table .badge.bg-success { background: #dcfce7; color: #166534; }
        .report-table .badge.bg-primary { background: #dbeafe; color: #1e40af; }
        .report-table .badge.bg-warning { background: #fef3c7; color: #92400e; }
        .report-table .badge.bg-danger  { background: #fee2e2; color: #991b1b; }
        .report-table .badge.bg-info    { background: #cffafe; color: #155e75; }
        .report-table .badge.bg-secondary { background: #f1f5f9; color: #475569; }
        .report-table .badge.bg-dark    { background: #1e293b; color: #f8fafc; }

        .report-table .progress {
            display: flex;
            height: 16px;
            border-radius: 4px;
            overflow: hidden;
            background: #e2e8f0;
        }

        .report-table .progress-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 600;
            color: #fff;
        }

        .report-table .progress-bar.bg-success { background: #22c55e; }
        .report-table .progress-bar.bg-primary { background: #3b82f6; }
        .report-table .progress-bar.bg-warning { background: #f59e0b; }
        .report-table .progress-bar.bg-secondary { background: #94a3b8; }

        /* ─── SUMMARY CARDS (for print) ─── */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .summary-card.bg-primary { background: #eff6ff; border-color: #bfdbfe; }
        .summary-card.bg-success { background: #f0fdf4; border-color: #bbf7d0; }
        .summary-card.bg-info    { background: #ecfeff; border-color: #a5f3fc; }
        .summary-card.bg-secondary { background: #f8fafc; border-color: #e2e8f0; }

        .summary-card .card-label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .summary-card .card-value {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .summary-card .card-value.text-primary { color: #2563eb; }
        .summary-card .card-value.text-success { color: #16a34a; }
        .summary-card .card-value.text-info    { color: #0891b2; }
        .summary-card .card-value.text-secondary { color: #64748b; }

        /* ─── REPORT FOOTER ─── */
        .report-footer {
            position: absolute;
            bottom: 15mm;
            left: 20mm;
            right: 20mm;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8px;
            color: #94a3b8;
        }

        .report-footer .page-number {
            font-weight: 600;
            color: #64748b;
        }

        .report-footer .confidential {
            font-style: italic;
        }

        /* ─── FILTER SUMMARY ─── */
        .filter-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 12px;
            margin-bottom: 12px;
            font-size: 9px;
        }

        .filter-summary .filter-label {
            font-weight: 600;
            color: #475569;
        }

        .filter-summary .filter-value {
            color: #0f172a;
        }

        /* ─── TOTAL RECORDS ─── */
        .records-count {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 10px;
        }

        /* ─── PRINT / PDF STYLES ─── */
        @media print {
            .no-print {
                display: none !important;
            }

            .print-only {
                display: block;
            }

            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .report-page {
                width: 210mm;
                margin: 0 auto;
                padding: 20mm 20mm 25mm;
                box-shadow: none;
            }

            .report-table thead {
                display: table-header-group;
            }

            .report-table tbody tr {
                page-break-inside: avoid;
            }

            .report-footer {
                position: fixed;
                bottom: 15mm;
                left: 20mm;
                right: 20mm;
            }

            @page {
                margin: 0;
                size: A4;
            }

            .summary-cards {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* ─── LANDSCAPE SUPPORT ─── */
        @media print and (orientation: landscape) {
            @page {
                size: A4 landscape;
            }
        }

        /* ─── RESPONSIVE ─── */
        @media screen and (max-width: 800px) {
            .report-page {
                width: 100%;
                margin: 56px 0 0;
                padding: 16px;
            }

            .report-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .report-header .report-badge {
                text-align: left;
            }

            .report-meta {
                flex-direction: column;
                gap: 6px;
            }

            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .preview-controls {
                flex-wrap: wrap;
                gap: 8px;
                padding: 10px 16px;
            }

            .preview-controls .btn-group {
                flex-wrap: wrap;
            }
        }

        @media screen and (max-width: 480px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Preview Controls Bar --}}
    <div class="preview-controls no-print">
        <div class="preview-title">
            <i class="fas fa-file-alt"></i> Print Preview
            <span style="font-weight:400;font-size:12px;color:#94a3b8;margin-left:8px">{{ $title ?? 'Report' }}</span>
        </div>
        <div class="btn-group">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ $pdfUrl ?? '#' }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ $backUrl ?? '#' }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Close
            </a>
        </div>
    </div>

    {{-- Report Content --}}
    <div class="report-page">
        {{-- Header --}}
        <div class="report-header">
            <div class="brand">
                <div class="brand-logo">
                    @php $logo = \App\Models\Setting::companyLogoSrc(); @endphp
                    @if($logo)
                        <img src="{{ $logo }}" alt="Logo">
                    @else
                        <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="brand-info">
                    <div class="org-name">{{ \App\Models\Setting::companyName() }}</div>
                    <div class="org-tagline">Asset Management System</div>
                </div>
            </div>
            <div class="report-badge">
                <div class="report-title">{{ $title ?? 'Report' }}</div>
                <div class="report-subtitle">{{ $description ?? '' }}</div>
            </div>
        </div>

        {{-- Meta --}}
        <div class="report-meta">
            <div class="meta-item">
                <span class="meta-label">Date Generated</span>
                <span class="meta-value">{{ now()->format('F d, Y h:i A') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Prepared By</span>
                <span class="meta-value">{{ auth()->user()->name ?? 'System' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Total Records</span>
                <span class="meta-value">{{ $totalRecords ?? 0 }}</span>
            </div>
            @if(isset($filterInfo) && $filterInfo)
            <div class="meta-item">
                <span class="meta-label">Filters</span>
                <span class="meta-value">{{ $filterInfo }}</span>
            </div>
            @endif
        </div>

        {{-- Body --}}
        @yield('report-content')

        {{-- Footer --}}
        <div class="report-footer">
            <span class="page-number">Page <span class="page-number-current">1</span> of <span class="page-number-total">1</span></span>
            <span>Generated {{ now()->format('F d, Y h:i A') }}</span>
            <span class="confidential">{{ \App\Models\Setting::companyName() }} &mdash; Internal Use</span>
        </div>
    </div>

    {{-- Page numbering via JS (browser preview only) --}}
    <script class="no-print">
        document.addEventListener('DOMContentLoaded', function() {
            var pageNumbers = document.querySelectorAll('.page-number-current');
            var pageTotals = document.querySelectorAll('.page-number-total');
            if (pageNumbers.length > 0) {
                pageNumbers.forEach(function(el) { el.textContent = '1'; });
            }
            if (pageTotals.length > 0) {
                pageTotals.forEach(function(el) { el.textContent = '1'; });
            }
        });
    </script>
</body>
</html>
