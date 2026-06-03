@extends('emails.layout')

@section('content')
    @php
        $isOverdue = $action === 'maintenance_overdue';
    @endphp

    <h2 style="color: {{ $isOverdue ? '#dc2626' : '#0D8ABC' }}; margin-top: 0;">
        @if($isOverdue)
            <span style="font-size: 18px;">&#9888;</span> Maintenance Overdue
        @else
            Maintenance Due
        @endif
    </h2>

    <p>
        @if($isOverdue)
            This asset's maintenance is <strong style="color: #dc2626;">overdue</strong>. Immediate attention is required.
        @else
            This asset has upcoming maintenance due. Please schedule accordingly.
        @endif
    </p>

    <table class="details">
        <tr>
            <th>Asset Name</th>
            <td>{{ $asset->name }}</td>
        </tr>
        <tr>
            <th>Asset Tag</th>
            <td>{{ $asset->asset_tag }}</td>
        </tr>
        @if($asset->serial_number)
        <tr>
            <th>Serial Number</th>
            <td>{{ $asset->serial_number }}</td>
        </tr>
        @endif
        <tr>
            <th>Status</th>
            <td><span class="badge badge-warning">{{ ucfirst($asset->status) }}</span></td>
        </tr>
        @if($dueDate)
        <tr>
            <th>{{ $isOverdue ? 'Due Date Was' : 'Due Date' }}</th>
            <td><strong>{{ $dueDate }}</strong></td>
        </tr>
        @endif
        @if($asset->department)
        <tr>
            <th>Department</th>
            <td>{{ $asset->department->name }}</td>
        </tr>
        @endif
        @if($asset->assignedUser)
        <tr>
            <th>Assigned To</th>
            <td>{{ $asset->assignedUser->name }}</td>
        </tr>
        @endif
        @if($notes)
        <tr>
            <th>Notes</th>
            <td>{{ $notes }}</td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        <a href="{{ route('assets.index') }}" class="button">View Asset</a>
    </p>
@endsection
