<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asset Details - {{ $asset->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1E40AF;
            --primary-light: #3B82F6;
            --primary-soft: #DBEAFE;
            --primary-dark: #1E3A8A;
            --bg: #F0F4F8;
            --surface: #FFFFFF;
            --text: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.08), 0 4px 10px rgba(0,0,0,0.05);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #EEF2F6 0%, #E2E8F0 100%);
            color: var(--text);
            min-height: 100vh;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup-card {
            width: 100%;
            max-width: 540px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .popup-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 24px;
            text-align: center;
            position: relative;
        }

        .popup-header h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 6px;
            letter-spacing: -0.5px;
        }

        .asset-tag-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 13px;
            display: inline-block;
        }

        .pulse-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 12px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .pulse-badge.bg-success {
            background-color: #22C55E !important;
            animation: pulse-green 2s infinite;
        }

        .pulse-badge.bg-primary {
            background-color: #3B82F6 !important;
            animation: pulse-blue 2s infinite;
        }

        .pulse-badge.bg-warning {
            background-color: #F59E0B !important;
            animation: pulse-amber 2s infinite;
            color: #1E293B;
        }

        .pulse-badge.bg-secondary {
            background-color: #64748B !important;
        }

        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        @keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        @keyframes pulse-amber {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        .popup-body {
            padding: 24px;
        }

        .image-container {
            width: 100%;
            height: 180px;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-container i {
            color: var(--text-muted);
        }

        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .detail-row:last-of-type {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 600;
        }

        .detail-value {
            font-weight: 700;
            color: var(--text);
            text-align: right;
        }

        .action-card {
            background: #F8FAFC;
            border: 1.5px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 16px;
            margin-top: 20px;
        }

        .action-card h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text);
        }

        .btn-action {
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .footer-logo {
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .footer-logo i {
            color: var(--primary-light);
            margin-right: 4px;
        }
    </style>
</head>
<body>

<div class="popup-card">
    <div class="popup-header">
        <span class="pulse-badge bg-{{ $asset->status === 'available' ? 'success' : ($asset->status === 'assigned' ? 'primary' : ($asset->status === 'maintenance' ? 'warning' : 'secondary')) }}">
            {{ $asset->status }}
        </span>
        <h1>{{ $asset->name }}</h1>
        <span class="asset-tag-badge">
            <i class="fas fa-tag me-1"></i> {{ $asset->asset_tag }}
        </span>
    </div>

    <div class="popup-body">
        @if($asset->image)
            <div class="image-container">
                <img src="{{ Storage::url($asset->image) }}" alt="{{ $asset->name }}">
            </div>
        @else
            <div class="image-container">
                <i class="fas fa-box fa-3x"></i>
            </div>
        @endif

        <div class="card p-3 border-0 bg-light rounded-3 mb-3">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-tags me-2"></i>Category</span>
                <span class="detail-value">{{ $asset->category->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-building me-2"></i>Department</span>
                <span class="detail-value">{{ $asset->department->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user me-2"></i>Assigned To</span>
                <span class="detail-value">{{ $asset->assignedUser->name ?? 'Unassigned' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-marker-alt me-2"></i>Location</span>
                <span class="detail-value">{{ $asset->location ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="card p-3 border-0 bg-light rounded-3">
            <div class="detail-row">
                <span class="detail-label">Serial Number</span>
                <span class="detail-value text-break" style="max-width: 200px;">{{ $asset->serial_number ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Manufacturer</span>
                <span class="detail-value">{{ $asset->manufacturer ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Model</span>
                <span class="detail-value">{{ $asset->model ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Warranty Expiry</span>
                <span class="detail-value">{{ $asset->warranty_expiry?->format('M d, Y') ?? 'N/A' }}</span>
            </div>
        </div>

        @if($asset->description)
            <div class="mt-3">
                <label class="detail-label d-block mb-1">Description</label>
                <div class="bg-light p-2 rounded-2 small text-muted">{{ $asset->description }}</div>
            </div>
        @endif

        @auth
            <!-- Check In/Out Quick Actions for Logged In Staff -->
            <div class="action-card">
                <h3><i class="fas fa-cog me-2"></i>Quick Actions</h3>
                <div class="d-flex gap-2 justify-content-center">
                    @if($asset->status === 'available')
                        <button type="button" class="btn btn-primary btn-action flex-grow-1" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="fas fa-check-circle me-1"></i> Check Out
                        </button>
                    @elseif($asset->status === 'assigned')
                        <form action="{{ route('assets.checkin', $asset) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-action w-100">
                                <i class="fas fa-undo me-1"></i> Check In
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary btn-action">
                        <i class="fas fa-external-link-alt"></i> Open Full View
                    </a>
                </div>
            </div>

            @if($asset->status === 'available')
                <!-- Checkout Modal -->
                <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Check Out Asset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('assets.checkout', $asset) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Assign To *</label>
                                        <select class="form-select" name="assigned_to" required>
                                            <option value="">Select User</option>
                                            @foreach(\App\Models\User::all() as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select class="form-select" name="department_id">
                                            <option value="">Select Department</option>
                                            @foreach(\App\Models\Department::all() as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Check Out</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Guest login invitation -->
            <div class="action-card text-center">
                <p class="mb-2 text-muted small"><i class="fas fa-lock me-1"></i> Are you a staff member?</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 rounded-pill">
                    <i class="fas fa-sign-in-alt me-1"></i> Log In to Manage Asset
                </a>
            </div>
        @endauth

        <div class="footer-logo">
            <i class="fas fa-cubes"></i> {{ \App\Models\Setting::companyName() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
