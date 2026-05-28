<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Scan Barcode / QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="scanModalStopScanner()"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-7 border-end">
                        <div class="p-3">
                            <div id="scan-modal-reader" style="width:100%;min-height:280px;background:#0d1117;border-radius:8px;overflow:hidden;position:relative;">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height:280px;">
                                    <i class="fas fa-camera fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-1 fw-semibold">Camera Scanner</p>
                                    <small>Click "Start" below to activate</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <button id="btn-scan-modal-start" class="btn btn-primary flex-fill btn-sm" onclick="scanModalStart()">
                                    <i class="fas fa-play me-1"></i> Start
                                </button>
                                <button id="btn-scan-modal-stop" class="btn btn-danger flex-fill btn-sm d-none" onclick="scanModalStop()">
                                    <i class="fas fa-stop me-1"></i> Stop
                                </button>
                            </div>
                            <div id="scan-modal-status" class="mt-2 small text-center text-muted d-none">
                                <span class="badge bg-success"><i class="fas fa-circle me-1"></i> Scanning...</span>
                            </div>
                            <div id="scan-modal-error" class="mt-2 alert alert-danger py-2 small d-none mb-0"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small"><i class="fas fa-keyboard me-1"></i> Manual Lookup</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="scan-modal-manual-input" class="form-control" placeholder="Type code / tag / serial..." autocomplete="off">
                                    <button class="btn btn-primary" type="button" onclick="scanModalLookup()"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div id="scan-modal-result" class="d-none">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <img id="scan-modal-result-img" src="" class="rounded d-none" style="width:48px;height:48px;object-fit:cover;">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold small text-truncate" id="scan-modal-result-name">—</div>
                                        <div><span class="badge" id="scan-modal-result-status">—</span></div>
                                    </div>
                                </div>
                                <table class="table table-sm small mb-2">
                                    <tr><td class="text-muted">Tag</td><td class="fw-semibold" id="scan-modal-result-tag">—</td></tr>
                                    <tr><td class="text-muted">Category</td><td id="scan-modal-result-category">—</td></tr>
                                    <tr><td class="text-muted">Department</td><td id="scan-modal-result-dept">—</td></tr>
                                    <tr><td class="text-muted">Assigned To</td><td id="scan-modal-result-assigned">—</td></tr>
                                    <tr><td class="text-muted">Location</td><td id="scan-modal-result-location">—</td></tr>
                                    <tr><td class="text-muted">Serial</td><td id="scan-modal-result-serial">—</td></tr>
                                </table>
                                <div class="d-flex gap-1">
                                    <a id="scan-modal-result-link" href="#" class="btn btn-sm btn-primary flex-fill" target="_blank"><i class="fas fa-eye me-1"></i> View</a>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="scanModalClear()"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div id="scan-modal-notfound" class="alert alert-warning py-2 small d-none mb-0">
                                <i class="fas fa-exclamation-circle me-1"></i> <span id="scan-modal-notfound-msg"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between py-2">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Supports CODE128, EAN, UPC, QR</small>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" onclick="scanModalStopScanner()">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanModalScanner = null;
let scanModalActive = false;
let scanModalCooldown = false;

function scanModalStart() {
    const reader = document.getElementById('scan-modal-reader');
    reader.innerHTML = '';
    reader.style.minHeight = '280px';

    document.getElementById('btn-scan-modal-start').classList.add('d-none');
    document.getElementById('btn-scan-modal-stop').classList.remove('d-none');
    document.getElementById('scan-modal-status').classList.remove('d-none');
    document.getElementById('scan-modal-error').classList.add('d-none');

    scanModalScanner = new Html5Qrcode('scan-modal-reader');
    scanModalScanner.start(
        { facingMode: 'environment' },
        {
            fps: 10,
            qrbox: { width: 240, height: 100 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.QR_CODE,
            ],
        },
        function onScanSuccess(decodedText) {
            if (scanModalCooldown) return;
            scanModalCooldown = true;
            setTimeout(() => scanModalCooldown = false, 2000);
            scanModalProcessScan(decodedText);
        },
        function onScanFailure() {}
    ).catch(function(err) {
        reader.innerHTML = '<div class="alert alert-danger text-center py-4 mb-0"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br><small>Camera unavailable. Use manual lookup.</small></div>';
        scanModalStop();
    });
}

function scanModalStop() {
    document.getElementById('btn-scan-modal-start').classList.remove('d-none');
    document.getElementById('btn-scan-modal-stop').classList.add('d-none');
    document.getElementById('scan-modal-status').classList.add('d-none');
    if (scanModalScanner) {
        try { scanModalScanner.stop().then(() => scanModalScanner.clear()).catch(() => {}); } catch(e) {}
        scanModalScanner = null;
    }
    scanModalActive = false;
}

function scanModalStopScanner() {
    scanModalStop();
    scanModalClear();
}

function scanModalLookup() {
    const val = document.getElementById('scan-modal-manual-input').value.trim();
    if (val) scanModalProcessScan(val);
}

function scanModalProcessScan(code) {
    fetch('{{ route("scanner.lookup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ term: code, type: 'qr_code' }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            document.getElementById('scan-modal-notfound-msg').textContent = data.error;
            document.getElementById('scan-modal-notfound').classList.remove('d-none');
            document.getElementById('scan-modal-result').classList.add('d-none');
        } else {
            scanModalShowResult(data.asset);
        }
    })
    .catch(() => {
        document.getElementById('scan-modal-notfound-msg').textContent = 'Network error. Please try again.';
        document.getElementById('scan-modal-notfound').classList.remove('d-none');
        document.getElementById('scan-modal-result').classList.add('d-none');
    });
}

function scanModalShowResult(a) {
    document.getElementById('scan-modal-result').classList.remove('d-none');
    document.getElementById('scan-modal-notfound').classList.add('d-none');

    document.getElementById('scan-modal-result-name').textContent = a.name || '—';
    document.getElementById('scan-modal-result-tag').textContent = a.asset_tag || '—';

    document.getElementById('scan-modal-result-category').textContent = a.category || '—';
    document.getElementById('scan-modal-result-dept').textContent = a.department || '—';
    document.getElementById('scan-modal-result-assigned').textContent = a.assigned_to || 'Unassigned';
    document.getElementById('scan-modal-result-location').textContent = a.location || '—';
    document.getElementById('scan-modal-result-serial').textContent = a.serial_number || '—';

    const badge = document.getElementById('scan-modal-result-status');
    badge.textContent = (a.status || '').charAt(0).toUpperCase() + (a.status || '').slice(1);
    badge.className = 'badge ' + (scanModalStatusBg(a.status));

    if (a.image) {
        document.getElementById('scan-modal-result-img').src = a.image;
        document.getElementById('scan-modal-result-img').classList.remove('d-none');
    } else {
        document.getElementById('scan-modal-result-img').classList.add('d-none');
    }

    document.getElementById('scan-modal-result-link').href = a.view_url || '#';
    document.getElementById('scan-modal-manual-input').value = '';
}

function scanModalStatusBg(s) {
    const map = {available:'bg-success',assigned:'bg-primary',maintenance:'bg-warning text-dark',retired:'bg-danger'};
    return map[s] || 'bg-secondary';
}

function scanModalClear() {
    document.getElementById('scan-modal-result').classList.add('d-none');
    document.getElementById('scan-modal-notfound').classList.add('d-none');
}

document.getElementById('scan-modal-manual-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); scanModalLookup(); }
});
</script>
@endpush
