<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asset->name }} — BPA Asset Info</title>
    <meta name="description" content="Asset details for {{ $asset->name }}, tag {{ $asset->asset_tag }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
            min-height: 100vh;
            color: #e2e8f0;
            padding: 1rem;
        }

        .card {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            max-width: 480px;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,.4);
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            padding: 1.5rem;
            text-align: center;
        }
        .card-header h1 { font-size: 1.25rem; font-weight: 700; color: #fff; }
        .card-header p  { font-size: .8rem; color: rgba(255,255,255,.7); margin-top: .25rem; }

        .asset-image {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
        }

        .asset-image-placeholder {
            background: rgba(255,255,255,.05);
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255,255,255,.2);
        }

        .card-body { padding: 1.5rem; }

        .asset-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: .25rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .85rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1rem;
        }
        .status-available   { background: rgba(34,197,94,.2);  color: #4ade80; border: 1px solid rgba(34,197,94,.3); }
        .status-assigned    { background: rgba(59,130,246,.2); color: #60a5fa; border: 1px solid rgba(59,130,246,.3); }
        .status-maintenance { background: rgba(234,179,8,.2);  color: #facc15; border: 1px solid rgba(234,179,8,.3); }
        .status-retired     { background: rgba(239,68,68,.2);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }
        @media (max-width: 380px) { .info-grid { grid-template-columns: 1fr; } }

        .info-item label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: rgba(255,255,255,.45);
            display: block;
            margin-bottom: .15rem;
        }
        .info-item span {
            font-size: .9rem;
            font-weight: 500;
            color: #e2e8f0;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .divider { border: none; border-top: 1px solid rgba(255,255,255,.08); margin: 1rem 0; }

        .footer {
            text-align: center;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: .7rem;
            color: rgba(255,255,255,.35);
        }

        .scan-time {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            font-size: .72rem;
            color: rgba(255,255,255,.4);
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div style="padding: 1.5rem 0; text-align:center; margin-bottom: .5rem;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.5);font-size:.8rem;">
            <i class="fas fa-shield-alt" style="color:#3b82f6;"></i>
            BPA Asset Management System
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1><i class="fas fa-box me-2"></i>Asset Information</h1>
            <p>Scanned via QR Code</p>
        </div>

        @if($asset->image)
            <img class="asset-image" src="{{ asset('storage/' . $asset->image) }}" alt="{{ $asset->name }}">
        @else
            <div class="asset-image-placeholder">
                <i class="fas fa-box"></i>
            </div>
        @endif

        <div class="card-body">
            <div class="asset-name">{{ $asset->name }}</div>

            @php
                $statusClass = match($asset->status) {
                    'available'   => 'status-available',
                    'assigned'    => 'status-assigned',
                    'maintenance' => 'status-maintenance',
                    'retired'     => 'status-retired',
                    default       => 'status-retired',
                };
                $statusIcon = match($asset->status) {
                    'available'   => 'fas fa-check-circle',
                    'assigned'    => 'fas fa-user',
                    'maintenance' => 'fas fa-tools',
                    'retired'     => 'fas fa-archive',
                    default       => 'fas fa-question-circle',
                };
            @endphp
            <div class="status-badge {{ $statusClass }}">
                <i class="{{ $statusIcon }}"></i>
                {{ ucfirst($asset->status) }}
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Asset Tag</label>
                    <span>{{ $asset->asset_tag }}</span>
                </div>
                @if($asset->serial_number)
                <div class="info-item">
                    <label>Serial Number</label>
                    <span>{{ $asset->serial_number }}</span>
                </div>
                @endif
                @if($asset->category)
                <div class="info-item">
                    <label>Category</label>
                    <span>{{ $asset->category->name }}</span>
                </div>
                @endif
                @if($asset->department)
                <div class="info-item">
                    <label>Department</label>
                    <span>{{ $asset->department->name }}</span>
                </div>
                @endif
                @if($asset->location)
                <div class="info-item">
                    <label>Location</label>
                    <span>{{ $asset->location }}</span>
                </div>
                @endif
                @if($asset->assignedUser)
                <div class="info-item">
                    <label>Assigned To</label>
                    <span>{{ $asset->assignedUser->name }}</span>
                </div>
                @endif
                @if($asset->manufacturer)
                <div class="info-item">
                    <label>Manufacturer</label>
                    <span>{{ $asset->manufacturer }}</span>
                </div>
                @endif
                @if($asset->model)
                <div class="info-item">
                    <label>Model</label>
                    <span>{{ $asset->model }}</span>
                </div>
                @endif
                @if($asset->warranty_expiry)
                <div class="info-item">
                    <label>Warranty Expiry</label>
                    <span>{{ $asset->warranty_expiry->format('d M Y') }}</span>
                </div>
                @endif
            </div>


        </div>

        <div class="footer">
            BPA Asset Management · Confidential
            <div class="scan-time">
                <i class="fas fa-clock"></i>
                Scanned at {{ now()->format('d M Y H:i') }}
            </div>
        </div>
    </div>

    <div style="text-align:center;margin-top:1.5rem;padding-bottom:2rem;">
        @auth
            <a href="{{ route('assets.show', $asset->id) }}"
               style="color:#3b82f6;font-size:.8rem;text-decoration:none;">
                <i class="fas fa-external-link-alt me-1"></i> View Full Details
            </a>
        @endauth
    </div>
</body>
</html>
