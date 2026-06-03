@extends('emails.layout')

@section('content')
    @php
        $actionLabels = [
            'created' => 'New Asset Added',
            'updated' => 'Asset Updated',
            'deleted' => 'Asset Removed',
            'checked_out' => 'Asset Checked Out',
            'checked_in' => 'Asset Checked In',
        ];
        $actionLabel = $actionLabels[$action] ?? 'Asset Notification';
    @endphp

    <h2 style="color: #0D8ABC; margin-top: 0;">{{ $actionLabel }}</h2>

    <p>An asset has been <strong>{{ $action }}</strong> in the system.</p>

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
            <td><span class="badge badge-{{ $asset->status === 'available' ? 'success' : ($asset->status === 'maintenance' ? 'warning' : 'info') }}">{{ ucfirst($asset->status) }}</span></td>
        </tr>
        @if($asset->category)
        <tr>
            <th>Category</th>
            <td>{{ $asset->category->name }}</td>
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
        @if($actor)
        <tr>
            <th>Action By</th>
            <td>{{ $actor->name }}</td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        <a href="{{ route('assets.index') }}" class="button">View Assets</a>
    </p>
@endsection
