@extends('emails.layout')

@section('content')
    @php
        $actionLabels = [
            'submitted' => 'New Asset Request Submitted',
            'approved' => 'Asset Request Approved',
            'rejected' => 'Asset Request Rejected',
            'issued' => 'Asset Issued to Requester',
            'cancelled' => 'Asset Request Cancelled',
        ];
        $actionLabel = $actionLabels[$action] ?? 'Asset Request Update';
        $actionColors = [
            'submitted' => 'badge-info',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'issued' => 'badge-info',
            'cancelled' => 'badge-warning',
        ];
        $actionColor = $actionColors[$action] ?? 'badge-info';
    @endphp

    <h2 style="color: #0D8ABC; margin-top: 0;">{{ $actionLabel }}</h2>

    <p>
        @switch($action)
            @case('submitted')
                A new asset request has been submitted and requires review.
            @break
            @case('approved')
                Your asset request has been <strong>approved</strong> and is now being processed.
            @break
            @case('rejected')
                Your asset request has been <strong>rejected</strong>. Please review the remarks for details.
            @break
            @case('issued')
                The requested asset has been <strong>issued</strong> to you.
            @break
            @case('cancelled')
                The asset request has been <strong>cancelled</strong>.
            @break
        @endswitch
    </p>

    <table class="details">
        <tr>
            <th>Asset Name</th>
            <td>{{ $assetRequest->asset_name }}</td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td>{{ $assetRequest->quantity }}</td>
        </tr>
        <tr>
            <th>Priority</th>
            <td><span class="badge {{ $actionColor }}">{{ $assetRequest->priority_display_name }}</span></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge {{ $actionColor }}">{{ $assetRequest->status_display_name }}</span></td>
        </tr>
        <tr>
            <th>Reason</th>
            <td>{{ $assetRequest->reason }}</td>
        </tr>
        @if($assetRequest->notes)
        <tr>
            <th>Notes</th>
            <td>{{ $assetRequest->notes }}</td>
        </tr>
        @endif
        @if($assetRequest->remarks)
        <tr>
            <th>Remarks</th>
            <td>{{ $assetRequest->remarks }}</td>
        </tr>
        @endif
        <tr>
            <th>Requested By</th>
            <td>{{ $assetRequest->user->name }}</td>
        </tr>
        <tr>
            <th>Requested At</th>
            <td>{{ $assetRequest->created_at->format('M d, Y g:i A') }}</td>
        </tr>
        @if($actor)
        <tr>
            <th>Action By</th>
            <td>{{ $actor->name }}</td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        <a href="{{ route('asset-requests.index') }}" class="button">View Asset Requests</a>
    </p>
@endsection
