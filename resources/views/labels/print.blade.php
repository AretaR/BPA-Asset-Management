<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label — {{ $asset->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .label-card { box-shadow: none !important; }
            @page { size: A4; margin: 10mm; }
        }

        .controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .controls button {
            padding: .5rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: .9rem;
        }
        .btn-print { background: #1e40af; color: #fff; }
        .btn-close-pg { background: #64748b; color: #fff; }

        /* ── Single label ─────────────────────────────────────────────── */
        .label-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.12);
            width: 100%;
            max-width: 380px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .label-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: #fff;
            padding: .65rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .label-header .org { font-size: .65rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; opacity: .85; }
        .label-header .tag { font-size: .85rem; font-weight: 700; }

        .label-body { padding: 1rem; }

        .asset-name-print {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .asset-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .4rem .75rem;
            margin-bottom: .75rem;
        }
        .meta-label { font-size: .6rem; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }
        .meta-value { font-size: .78rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .codes-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            border-top: 1px dashed #e2e8f0;
            padding-top: .75rem;
        }

        .barcode-wrap {
            flex: 1;
            text-align: center;
        }
        .barcode-wrap svg,
        .barcode-wrap img { max-width: 100%; height: 50px; }
        .barcode-number { font-size: .6rem; color: #64748b; margin-top: .2rem; letter-spacing: 1px; }

        .qr-wrap {
            flex-shrink: 0;
            text-align: center;
        }
        .qr-wrap svg,
        .qr-wrap img { width: 70px; height: 70px; }
        .qr-label { font-size: .55rem; color: #94a3b8; margin-top: .15rem; }

        .label-footer {
            background: #f8fafc;
            padding: .4rem 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .label-footer small { font-size: .6rem; color: #94a3b8; }

        .status-dot {
            width: 7px; height: 7px; border-radius: 50%;
            display: inline-block; margin-right: 4px;
        }
        .dot-available   { background: #22c55e; }
        .dot-assigned    { background: #3b82f6; }
        .dot-maintenance { background: #f59e0b; }
        .dot-retired     { background: #ef4444; }
    </style>
</head>
<body>

    <div class="controls no-print">
        <button class="btn-print" onclick="window.print()">🖨 Print Label</button>
        <button class="btn-close-pg" onclick="window.close()">✕ Close</button>
    </div>

    <div class="label-card">
        {{-- Header --}}
        <div class="label-header">
            <span class="org">BPA Asset Management</span>
            <span class="tag">{{ $asset->asset_tag }}</span>
        </div>

        {{-- Body --}}
        <div class="label-body">
            <div class="asset-name-print">{{ $asset->name }}</div>

            <div class="asset-meta">
                @if($asset->category)
                <div>
                    <div class="meta-label">Category</div>
                    <div class="meta-value">{{ $asset->category->name }}</div>
                </div>
                @endif
                @if($asset->department)
                <div>
                    <div class="meta-label">Department</div>
                    <div class="meta-value">{{ $asset->department->name }}</div>
                </div>
                @endif
                @if($asset->location)
                <div>
                    <div class="meta-label">Location</div>
                    <div class="meta-value">{{ $asset->location }}</div>
                </div>
                @endif
                <div>
                    <div class="meta-label">Status</div>
                    <div class="meta-value">
                        <span class="status-dot dot-{{ $asset->status }}"></span>
                        {{ ucfirst($asset->status) }}
                    </div>
                </div>
                @if($asset->serial_number)
                <div class="col-span-2">
                    <div class="meta-label">Serial</div>
                    <div class="meta-value">{{ $asset->serial_number }}</div>
                </div>
                @endif
            </div>

            {{-- Barcode + QR Row --}}
            <div class="codes-row">
                <div class="barcode-wrap">
                    @if($asset->barcode)
                        {!! $barcodeSvg ?? $svg ?? '' !!}
                        <div class="barcode-number">{{ $asset->barcode }}</div>
                    @endif
                </div>
                @isset($svgQr)
                <div class="qr-wrap">
                    {!! $svgQr !!}
                    <div class="qr-label">Scan QR</div>
                </div>
                @endisset
            </div>
        </div>

        {{-- Footer --}}
        <div class="label-footer">
            <small>Generated: {{ now()->format('d M Y') }}</small>
            <small>ID: {{ $asset->id }}</small>
        </div>
    </div>

</body>
</html>
