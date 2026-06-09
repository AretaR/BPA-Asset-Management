@extends('emails.layout')

@section('content')
    <h2 style="color: #0D8ABC; margin-top: 0; word-wrap: break-word;">User {{ ucfirst($action) }}</h2>

    <p>A user account has been <strong>{{ $action }}</strong> in the system.</p>

    <table class="details">
        <tr>
            <th>Name</th>
            <td style="word-break: break-word;">{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td style="word-break: break-word;">{{ $user->email }}</td>
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
            <td style="word-break: break-word;">{{ $user->employee_id }}</td>
        </tr>
        @endif
        @if($user->phone)
        <tr>
            <th>Phone</th>
            <td style="word-break: break-word;">{{ $user->phone }}</td>
        </tr>
        @endif
        @if($user->position)
        <tr>
            <th>Position</th>
            <td style="word-break: break-word;">{{ $user->position }}</td>
        </tr>
        @endif
        @if(!empty($changes) || ($passwordOnly ?? false))
        <tr>
            <th style="vertical-align: top;">Changes Made</th>
            <td style="word-break: break-word;">
                @if($passwordOnly ?? false)
                    <span style="color: #6b7280; font-style: italic;">• Password was updated.</span>
                @else
                    <table style="width:100%; border-collapse: collapse;">
                        @php
                            $fieldLabels = [
                                'name' => 'Name',
                                'email' => 'Email',
                                'role' => 'Role',
                                'department_id' => 'Department',
                                'employee_id' => 'Employee ID',
                                'phone' => 'Phone',
                                'position' => 'Position',
                            ];
                        @endphp
                        @foreach($changes as $field => $change)
                            @if(is_array($change) && count($change) === 2)
                                @php
                                    $oldVal = $change[0];
                                    $newVal = $change[1];
                                    $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));

                                    if ($field === 'department_id') {
                                        $oldDept = $oldVal ? \App\Models\Department::find($oldVal) : null;
                                        $newDept = $newVal ? \App\Models\Department::find($newVal) : null;
                                        $oldVal = $oldDept ? $oldDept->name : 'None';
                                        $newVal = $newDept ? $newDept->name : 'None';
                                    }

                                    if ($field === 'role') {
                                        $oldVal = $oldVal ? str_replace('_', ' ', ucfirst($oldVal)) : 'None';
                                        $newVal = $newVal ? str_replace('_', ' ', ucfirst($newVal)) : 'None';
                                    }
                                @endphp
                                <tr>
                                    <td style="padding: 2px 8px 2px 0; white-space: nowrap; vertical-align: top; color: #6b7280; font-size: 13px;">• {{ $label }}:</td>
                                    <td style="padding: 2px 0; word-break: break-word; font-size: 13px;">
                                        <span style="color: #991b1b; text-decoration: line-through;">{{ $oldVal }}</span>
                                        <span style="color: #6b7280; margin: 0 4px;">→</span>
                                        <span style="color: #065f46; font-weight: 600;">{{ $newVal }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                @endif
            </td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        <a href="{{ route('users.index') }}" class="button">View Users</a>
    </p>
@endsection
