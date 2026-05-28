@extends('layouts.app')

@section('title', 'Asset Scanner')

@push('styles')
<style>
/* ── Scanner Page ─────────────────────────────────────────────────── */
.scanner-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.scanner-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 70% 50%, rgba(59,130,246,.25) 0%, transparent 70%);
    pointer-events: none;
}
.scanner-hero h1 { color: #fff; font-size: 1.75rem; font-weight: 700; margin-bottom: .25rem; }
.scanner-hero p  { color: rgba(255,255,255,.65); margin: 0; }

/* Camera feed container */
#camera-region {
    background: #0d1117;
    border-radius: 12px;
    overflow: hidden;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 2px solid rgba(59,130,246,.3);
    transition: border-color .3s;
}
#camera-region.scanning {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,.15);
}
#camera-region video { width: 100%; border-radius: 10px; }

/* Scan overlay line animation */
.scan-line {
    position: absolute;
    left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    animation: scanline 2s linear infinite;
    display: none;
    z-index: 10;
}
.scanning .scan-line { display: block; }
@keyframes scanline {
    0%   { top: 10%; }
    100% { top: 90%; }
}

.scan-corner {
    position: absolute;
    width: 30px; height: 30px;
    border-color: #3b82f6;
    border-style: solid;
    pointer-events: none;
    z-index: 10;
    display: none;
}
.scanning .scan-corner { display: block; }
.scan-corner.tl { top: 16px; left: 16px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 4px; }
.scan-corner.tr { top: 16px; right: 16px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
.scan-corner.bl { bottom: 16px; left: 16px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
.scan-corner.br { bottom: 16px; right: 16px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

/* Result card */
#asset-result-card {
    display: none;
    border: 2px solid rgba(34,197,94,.4);
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(34,197,94,.08), rgba(16,185,129,.04));
    animation: slideUp .3s ease;
}
@keyframes slideUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}

/* Status badges */
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .4px;
    text-transform: uppercase;
}

/* Quick search */
#quick-search-results { position: absolute; width: 100%; z-index: 999; }

/* Pulse dot */
.pulse-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: #22c55e;
    display: inline-block;
    margin-right: 6px;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.5; transform:scale(.8); }
}

/* stat card */
.stat-mini {
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    transition: transform .2s;
}
.stat-mini:hover { transform: translateY(-3px); }
</style>
@endpush

@section('content')
<div class="scanner-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1><i class="fas fa-qrcode me-2"></i>Asset Scanner</h1>
            <p>Scan a QR code to look up asset details instantly</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('scanner.history') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-history me-1"></i> Scan History
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ── Left: Camera scanner ──────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-camera me-2 text-primary"></i>
                    <strong>Camera Scanner</strong>
                </div>
                <div id="scan-status-dot" style="display:none;">
                    <span class="pulse-dot"></span>
                    <small class="text-success fw-semibold">Scanning…</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-3">

                {{-- Camera region --}}
                <div id="camera-region">
                    <div class="scan-line"></div>
                    <div class="scan-corner tl"></div>
                    <div class="scan-corner tr"></div>
                    <div class="scan-corner bl"></div>
                    <div class="scan-corner br"></div>
                    <div id="camera-placeholder" class="text-center text-muted py-5 px-3">
                        <i class="fas fa-camera fa-4x mb-3" style="opacity:.3;"></i>
                        <p class="mb-0">Press <strong>Start Scanning</strong> to activate camera</p>
                        <small>Works on all mobile browsers & desktops</small>
                    </div>
                </div>

                {{-- Controls --}}
                <div class="d-flex gap-2 flex-wrap">
                    <button id="btn-start-scan" class="btn btn-primary flex-fill" onclick="startScanner()">
                        <i class="fas fa-play me-2"></i>Start Scanning
                    </button>
                    <button id="btn-stop-scan" class="btn btn-danger flex-fill d-none" onclick="stopScanner()">
                        <i class="fas fa-stop me-2"></i>Stop
                    </button>
                </div>

                {{-- Scan type selector --}}
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <small class="text-muted fw-semibold">Scan type:</small>
                    <div class="btn-group btn-group-sm" id="scan-type-group">
                        <input type="radio" class="btn-check" name="scan_type" id="type-qr" value="qr_code" checked>
                        <label class="btn btn-outline-info" for="type-qr"><i class="fas fa-qrcode me-1"></i>QR Code</label>

                        <input type="radio" class="btn-check" name="scan_type" id="type-serial" value="serial_number">
                        <label class="btn btn-outline-warning" for="type-serial"><i class="fas fa-hashtag me-1"></i>Serial</label>

                        <input type="radio" class="btn-check" name="scan_type" id="type-tag" value="asset_tag">
                        <label class="btn btn-outline-secondary" for="type-tag"><i class="fas fa-tag me-1"></i>Tag</label>
                    </div>
                </div>

                {{-- Camera error area --}}
                <div id="camera-error" class="alert alert-danger d-none py-2" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><span id="camera-error-msg"></span>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Right: Manual input + result ────────────────────────────────── --}}
    <div class="col-lg-6 d-flex flex-column gap-4">

        {{-- Manual entry --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-keyboard me-2 text-secondary"></i><strong>Manual Lookup</strong>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <input type="text" id="manual-input"
                           class="form-control form-control-lg pe-5"
                           placeholder="Scan or type QR code / serial / asset tag…"
                           autocomplete="off">
                    <button class="btn btn-primary position-absolute end-0 top-0 h-100 px-3 rounded-start-0"
                            onclick="manualLookup()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                {{-- Quick search results --}}
                <div class="position-relative">
                    <div id="quick-search-results" class="card shadow-lg border-0 d-none">
                        <div class="list-group list-group-flush rounded" id="search-results-list"></div>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Supports: QR code, asset tag, serial number</small>
                </div>
            </div>
        </div>

        {{-- Asset result --}}
        <div class="card p-3" id="asset-result-card">
            <div class="d-flex align-items-start gap-3">
                <div id="result-img-wrap">
                    <img id="result-image" src="" alt="Asset"
                         class="rounded-3 d-none"
                         style="width:80px;height:80px;object-fit:cover;">
                    <div id="result-img-placeholder"
                         class="rounded-3 d-flex align-items-center justify-content-center bg-light"
                         style="width:80px;height:80px;">
                        <i class="fas fa-box fa-2x text-muted"></i>
                    </div>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h5 class="mb-0 text-truncate" id="result-name">—</h5>
                        <span class="status-badge" id="result-status-badge">—</span>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <small class="text-muted d-block">Asset Tag</small>
                            <span class="fw-semibold" id="result-asset-tag">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Serial #</small>
                            <span class="fw-semibold" id="result-barcode">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Category</small>
                            <span id="result-category">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Department</small>
                            <span id="result-department">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Assigned To</small>
                            <span id="result-assigned">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Location</small>
                            <span id="result-location">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Manufacturer</small>
                            <span id="result-manufacturer">—</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Model</small>
                            <span id="result-model">—</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a id="result-view-link" href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> View Full Details
                        </a>
                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="clearResult()">
                            <i class="fas fa-times me-1"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error result --}}
        <div id="scan-error-card" class="alert alert-danger d-none">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="scan-error-msg"></span>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner = null;
let isScanning = false;
let scanCooldown = false;

function getScanType() {
    return document.querySelector('input[name="scan_type"]:checked')?.value ?? 'qr_code';
}

function startScanner() {
    const region = document.getElementById('camera-region');
    const placeholder = document.getElementById('camera-placeholder');

    placeholder.style.display = 'none';
    region.classList.add('scanning');
    document.getElementById('btn-start-scan').classList.add('d-none');
    document.getElementById('btn-stop-scan').classList.remove('d-none');
    document.getElementById('scan-status-dot').style.display = 'flex';
    document.getElementById('camera-error').classList.add('d-none');

    // Create scanner container
    let container = document.getElementById('qr-reader');
    if (!container) {
        container = document.createElement('div');
        container.id = 'qr-reader';
        container.style.width = '100%';
        region.appendChild(container);
    }

    const scanType = getScanType();
    const formats = scanType === 'qr_code'
        ? [Html5QrcodeSupportedFormats.QR_CODE]
        : [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.QR_CODE,
          ];

    scanner = new Html5Qrcode('qr-reader');
    scanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 260, height: 100 }, formatsToSupport: formats },
        onScanSuccess,
        () => {}
    ).then(() => {
        isScanning = true;
    }).catch(err => {
        showCameraError('Camera unavailable. Please allow camera access or use manual input.');
        stopScanner();
    });
}

function stopScanner() {
    const region = document.getElementById('camera-region');
    region.classList.remove('scanning');
    document.getElementById('btn-start-scan').classList.remove('d-none');
    document.getElementById('btn-stop-scan').classList.add('d-none');
    document.getElementById('scan-status-dot').style.display = 'none';

    if (scanner && isScanning) {
        scanner.stop().then(() => scanner.clear()).catch(() => {});
        isScanning = false;
        scanner = null;
    }
}

function onScanSuccess(decodedText) {
    if (scanCooldown) return;
    scanCooldown = true;
    setTimeout(() => scanCooldown = false, 2000);

    lookupAsset(decodedText, getScanType());
}

function manualLookup() {
    const val = document.getElementById('manual-input').value.trim();
    if (!val) return;
    lookupAsset(val, getScanType());
}

function lookupAsset(term, type) {
    fetch('{{ route("scanner.lookup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ term, type }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            showScanError(data.error);
            clearResult();
        } else {
            showResult(data.asset);
            hideScanError();
        }
    })
    .catch(() => showScanError('Network error. Please try again.'));
}

function showResult(a) {
    document.getElementById('asset-result-card').style.display = 'flex';
    document.getElementById('scan-error-card').classList.add('d-none');

    document.getElementById('result-name').textContent       = a.name ?? '—';
    document.getElementById('result-asset-tag').textContent  = a.asset_tag ?? '—';
    document.getElementById('result-barcode').textContent    = a.serial_number ?? '—';
    document.getElementById('result-category').textContent   = a.category ?? '—';
    document.getElementById('result-department').textContent = a.department ?? '—';
    document.getElementById('result-assigned').textContent   = a.assigned_to ?? 'Unassigned';
    document.getElementById('result-location').textContent   = a.location ?? '—';
    document.getElementById('result-manufacturer').textContent = a.manufacturer ?? '—';
    document.getElementById('result-model').textContent      = a.model ?? '—';
    document.getElementById('result-view-link').href         = a.view_url ?? '#';

    const badge = document.getElementById('result-status-badge');
    badge.textContent = (a.status ?? '').charAt(0).toUpperCase() + (a.status ?? '').slice(1);
    badge.className = 'status-badge text-white ' + (statusBg(a.status));

    if (a.image) {
        document.getElementById('result-image').src = a.image;
        document.getElementById('result-image').classList.remove('d-none');
        document.getElementById('result-img-placeholder').classList.add('d-none');
    } else {
        document.getElementById('result-image').classList.add('d-none');
        document.getElementById('result-img-placeholder').classList.remove('d-none');
    }
}

function statusBg(s) {
    const map = {available:'bg-success',assigned:'bg-primary',maintenance:'bg-warning text-dark',retired:'bg-danger',lost:'bg-dark'};
    return map[s] ?? 'bg-secondary';
}

function clearResult() {
    document.getElementById('asset-result-card').style.display = 'none';
    document.getElementById('manual-input').value = '';
}

function showScanError(msg) {
    document.getElementById('scan-error-card').classList.remove('d-none');
    document.getElementById('scan-error-msg').textContent = msg;
    document.getElementById('asset-result-card').style.display = 'none';
}

function hideScanError() {
    document.getElementById('scan-error-card').classList.add('d-none');
}

function showCameraError(msg) {
    document.getElementById('camera-error').classList.remove('d-none');
    document.getElementById('camera-error-msg').textContent = msg;
}

// ── Quick autocomplete search ─────────────────────────────────────────────────
let searchTimeout = null;
document.getElementById('manual-input').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) {
        hideSearchResults();
        return;
    }
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('scanner.search') }}?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(results => {
            const list = document.getElementById('search-results-list');
            const box  = document.getElementById('quick-search-results');
            list.innerHTML = '';
            if (!results.length) { box.classList.add('d-none'); return; }
            results.forEach(a => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action py-2 px-3';
                item.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-box text-muted"></i>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate">${a.name}</div>
                            <small class="text-muted">${a.asset_tag} · ${a.category ?? ''}</small>
                        </div>
                        <span class="badge bg-secondary">${a.status}</span>
                    </div>`;
                item.addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById('manual-input').value = a.asset_tag;
                    hideSearchResults();
                    lookupAsset(a.asset_tag, 'asset_tag');
                });
                list.appendChild(item);
            });
            box.classList.remove('d-none');
        });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('#quick-search-results') && !e.target.closest('#manual-input')) {
        hideSearchResults();
    }
});

function hideSearchResults() {
    document.getElementById('quick-search-results').classList.add('d-none');
}

document.getElementById('manual-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); manualLookup(); }
});
</script>
@endpush
