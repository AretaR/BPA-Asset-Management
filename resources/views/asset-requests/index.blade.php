@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="page-header mb-0">
        <i class="fas fa-clipboard-list me-2"></i> Asset Requests
    </h1>
    @can('create', \App\Models\AssetRequest::class)
    <a href="{{ route('asset-requests.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Request Asset
    </a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-filter me-2"></i> Filters
    </div>
    <div class="card-body text-white">
        <form method="GET" action="{{ route('asset-requests.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       value="{{ request('search') }}" placeholder="Asset name, reason...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('asset-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i> All Requests ({{ $requests->total() }})
    </div>
    <div class="card-body text-white">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Qty</th>
                        <th>Priority</th>
                        <th>Status</th>
                        @can('approve', \App\Models\AssetRequest::class)
                        <th class="d-none d-md-table-cell">Requester</th>
                        @endcan
                        <th class="d-none d-md-table-cell">Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td>
                            <a href="{{ route('asset-requests.show', $req) }}">
                                <strong>{{ $req->asset_name }}</strong>
                            </a>
                        </td>
                        <td>{{ $req->quantity }}</td>
                        <td>
                            <span class="badge {{ $req->priority_badge_class }}">
                                {{ $req->priority_display_name }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $req->status_badge_class }}">
                                {{ $req->status_display_name }}
                            </span>
                        </td>
                        @can('approve', \App\Models\AssetRequest::class)
                        <td class="d-none d-md-table-cell">{{ $req->user->name }}</td>
                        @endcan
                        <td class="d-none d-md-table-cell">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('asset-requests.show', $req) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('approve', \App\Models\AssetRequest::class)
                                @if($req->status === 'pending')
                                <button type="button" class="btn btn-outline-success"
                                        data-bs-toggle="modal" data-bs-target="#actionModal"
                                        data-url="{{ route('asset-requests.update-status', $req) }}"
                                        data-action="approved">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#actionModal"
                                        data-url="{{ route('asset-requests.update-status', $req) }}"
                                        data-action="rejected">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                @if($req->status === 'approved')
                                <button type="button" class="btn btn-outline-info"
                                        data-bs-toggle="modal" data-bs-target="#actionModal"
                                        data-url="{{ route('asset-requests.update-status', $req) }}"
                                        data-action="issued">
                                    <i class="fas fa-box"></i>
                                </button>
                                @endif
                                @endcan
                                @if($req->status === 'pending' && $req->user_id === auth()->id())
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="if(confirm('Cancel this request?')){ document.getElementById('cancel-form-{{ $req->id }}').submit(); }">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <form id="cancel-form-{{ $req->id }}" action="{{ route('asset-requests.update-status', $req) }}" method="POST" class="d-none">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <p>No requests found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $requests->withQueryString()->links() }}
        </div>
    </div>
</div>

@can('approve', \App\Models\AssetRequest::class)
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="actionForm" method="POST">
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
            const url = button.getAttribute('data-url');
            const action = button.getAttribute('data-action');
            const form = document.getElementById('actionForm');
            const statusInput = document.getElementById('actionStatus');
            const actionLabel = document.getElementById('actionLabel');
            const submitBtn = document.getElementById('actionSubmitBtn');
            const message = document.getElementById('actionMessage');

            form.action = url;
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
