@extends('reports.print-layout')

@php
    $title = 'Assets by Status';
    $description = 'Asset count categorized by current status';
    $totalCount = array_sum(array_column($statusData, 'count'));
    $totalRecords = count($statusData);
    $filterInfo = 'All statuses';
    $backUrl = route('reports.assets_by_status');
    $pdfUrl = route('reports.assets_by_status', ['format' => 'pdf'] + request()->query());
@endphp

@section('report-content')
    <div class="records-count">Showing {{ $totalRecords }} status categor{{ $totalRecords !== 1 ? 'ies' : 'y' }} &mdash; {{ $totalCount }} total assets</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-center">Count</th>
                <th class="text-end">Total Value</th>
                <th class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusData as $status => $data)
            @php
                $percentage = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
                $badgeClass = match($status) {
                    'available' => 'bg-success',
                    'assigned' => 'bg-primary',
                    'maintenance' => 'bg-warning',
                    'retired' => 'bg-secondary',
                    default => 'bg-secondary'
                };
            @endphp
            <tr>
                <td><span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                <td class="text-center"><strong>{{ $data['count'] }}</strong></td>
                <td class="text-end">${{ number_format($data['total_value'], 2) }}</td>
                <td style="width:200px;">
                    <div class="progress">
                        <div class="progress-bar {{ $badgeClass }}" style="width: {{ $percentage }}%">
                            {{ $percentage }}%
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
