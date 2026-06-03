@extends('emails.layout')

@section('content')
    <h2 style="color: #0D8ABC; margin-top: 0;">User {{ ucfirst($action) }}</h2>

    <p>A user account has been <strong>{{ $action }}</strong> in the system.</p>

    <table class="details">
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td><span class="badge badge-info">{{ $user->role_display_name }}</span></td>
        </tr>
        @if($user->department)
        <tr>
            <th>Department</th>
            <td>{{ $user->department->name }}</td>
        </tr>
        @endif
        @if($user->employee_id)
        <tr>
            <th>Employee ID</th>
            <td>{{ $user->employee_id }}</td>
        </tr>
        @endif
        @if(!empty($changes))
        <tr>
            <th>Changes Made</th>
            <td>
                <ul style="margin:0; padding-left:16px;">
                @foreach($changes as $field => $change)
                    <li><strong>{{ $field }}</strong>: {{ is_array($change) ? implode(' -> ', $change) : $change }}</li>
                @endforeach
                </ul>
            </td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        <a href="{{ route('users.index') }}" class="button">View Users</a>
    </p>
@endsection
