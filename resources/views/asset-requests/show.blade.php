@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="page-header mb-0">
        <i class="fas fa-clipboard me-2"></i> {{ $assetRequest->asset_name }}
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('asset-requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        @can('approve', \App\Models\AssetRequest::class)
        @if($assetRequest->status === 'pending')
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#actionModal"
                data-action="approved">
            <i class="fas fa-check me-2"></i> Approve
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#actionModal"
                data-action="rejected">
            <i class="fas fa-times me-2"></i> Reject
        </button>
        @endif
        @if($assetRequest->status === 'approved')
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#actionModal"
                data-action="issued">
            <i class="fas fa-box me-2"></i> Mark as Issued
        </button>
        @endif
        @endcan
        @if($assetRequest->status === 'pending' && $assetRequest->user_id === auth()->id())
        <form action="{{ route('asset-requests.update-status', $assetRequest) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Cancel this request?')">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-ban me-2"></i> Cancel Request
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Request Details
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <label class="text-muted small">Asset Name</label>
                    <div class="fw-bold">{{ $assetRequest->asset_name }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Quantity</label>
                    <div class="fw-bold">{{ $assetRequest->quantity }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Priority</label>
                    <div><span class="badge {{ $assetRequest->priority_badge_class }}">{{ $assetRequest->priority_display_name }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Status</label>
                    <div><span class="badge {{ $assetRequest->status_badge_class }}">{{ $assetRequest->status_display_name }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Reason</label>
                    <div>{{ $assetRequest->reason }}</div>
                </div>
                @if($assetRequest->notes)
                <div class="mb-3">
                    <label class="text-muted small">Notes</label>
                    <div>{{ $assetRequest->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock me-2"></i> Timeline
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <label class="text-muted small">Requested By</label>
                    <div class="fw-bold">{{ $assetRequest->user->name }}</div>
                    <div class="small text-muted">{{ $assetRequest->created_at->format('M d, Y g:i A') }}</div>
                </div>

                @if($assetRequest->approved_by)
                <div class="mb-3">
                    <label class="text-muted small">Approved By</label>
                    <div class="fw-bold">{{ $assetRequest->approver?->name ?? 'N/A' }}</div>
                    <div class="small text-muted">{{ $assetRequest->approved_at?->format('M d, Y g:i A') }}</div>
                </div>
                @endif

                @if($assetRequest->issued_by)
                <div class="mb-3">
                    <label class="text-muted small">Issued By</label>
                    <div class="fw-bold">{{ $assetRequest->issuer?->name ?? 'N/A' }}</div>
                    <div class="small text-muted">{{ $assetRequest->issued_at?->format('M d, Y g:i A') }}</div>
                </div>
                @endif

                @if($assetRequest->remarks)
                <div class="mb-0">
                    <label class="text-muted small">Remarks</label>
                    <div>{{ $assetRequest->remarks }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i> Status History
            </div>
            <div class="card-body text-white">
                @php
                    $logs = \App\Models\ActivityLog::where('model_type', \App\Models\AssetRequest::class)
                        ->where('model_id', $assetRequest->id)
                        ->with('user')
                        ->latest()
                        ->get();
                @endphp

                @if($logs->count() > 0)
                <div class="timeline">
                    @foreach($logs as $log)
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="mt-1">
                            <i class="fas fa-{{ $log->action_icon }} text-{{ $log->action_color }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">
                                @switch($log->action)
                                    @case('created') Submitted @break
                                    @case('updated') Status Updated @break
                                    @default {{ ucfirst($log->action) }}
                                @endswitch
                            </div>
                            <div class="small text-muted">
                                by {{ $log->user?->name ?? 'System' }}
                            </div>
                            <div class="small text-muted">
                                {{ $log->created_at->format('M d, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No history recorded.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@can('approve', \App\Models\AssetRequest::class)
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('asset-requests.update-status', $assetRequest) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" id="actionStatus">
                <div class="modal-header">
                    <h5 class="modal-title">Update Request Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="actionMessage">Are you sure you want to <strong id="actionLabel"></strong> this request?</p>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Add remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="actionSubmitBtn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
@can('approve', \App\Models\AssetRequest::class)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const actionModal = document.getElementById('actionModal');
    if (actionModal) {
        actionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const statusInput = document.getElementById('actionStatus');
            const actionLabel = document.getElementById('actionLabel');
            const submitBtn = document.getElementById('actionSubmitBtn');
            const message = document.getElementById('actionMessage');

            statusInput.value = action;

            const labels = { approved: 'approve', rejected: 'reject', issued: 'mark as issued' };
            const btnClasses = { approved: 'btn-success', rejected: 'btn-danger', issued: 'btn-info' };
            const messages = {
                approved: 'Are you sure you want to <strong>approve</strong> this request?',
                rejected: 'Are you sure you want to <strong>reject</strong> this request?',
                issued: 'Are you sure you want to <strong>mark as issued</strong> this request?'
            };

            actionLabel.textContent = labels[action] || action;
            message.innerHTML = messages[action] || 'Update this request?';
            submitBtn.className = 'btn ' + (btnClasses[action] || 'btn-primary');
        });
    }
});
</script>
@endcan
@endpush
