@extends('reports.print-layout')

@php
    $title = 'Activity Logs';
    $description = 'System activity and audit trail';
    $totalRecords = $activities->total();
    $filters = [];
    if (request('action')) $filters[] = 'Action: ' . ucfirst(request('action'));
    if (request('user_id')) $filters[] = 'User ID: ' . request('user_id');
    if (request('date_from')) $filters[] = 'From: ' . request('date_from');
    if (request('date_to')) $filters[] = 'To: ' . request('date_to');
    $filterInfo = $filters ? implode(' | ', $filters) : 'All logs';
    $backUrl = route('reports.activity_logs', request()->except(['preview', 'page']));
    $pdfUrl = route('reports.activity_logs', ['format' => 'pdf'] + request()->except('preview'));
@endphp

@section('report-content')
    <div class="records-count">Showing {{ $totalRecords }} log entr{{ $totalRecords !== 1 ? 'ies' : 'y' }}</div>

    @if($filters)
    <div class="filter-summary">
        @foreach($filters as $filter)
            <span><span class="filter-label">{{ explode(': ', $filter)[0] }}:</span> <span class="filter-value">{{ explode(': ', $filter)[1] ?? '' }}</span></span>
        @endforeach
    </div>
    @endif

    <table class="report-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>IP Address</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
            <tr>
                <td>{{ $activity->user->name ?? 'System' }}</td>
                <td><span class="badge bg-{{ $activity->action_color ?? 'secondary' }}">{{ ucfirst($activity->action) }}</span></td>
                <td>
                    @if($activity->model_type)
                        @php $modelClass = class_basename($activity->model_type); @endphp
                        {{ $modelClass }} #{{ $activity->model_id }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#94a3b8;padding:24px;">No activity logs found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
