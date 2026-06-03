<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BPA Asset Management' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 10px;
        }
        .header {
            background: linear-gradient(135deg, #0D8ABC 0%, #0a6d94 100%);
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #fff;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }
        .header p {
            color: rgba(255,255,255,0.85);
            margin: 5px 0 0;
            font-size: 14px;
        }
        .body {
            background-color: #ffffff;
            padding: 30px;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #6b7280;
        }
        .footer a {
            color: #0D8ABC;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0D8ABC;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        .button:hover {
            background-color: #0a6d94;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table.details th,
        table.details td {
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        table.details th {
            font-weight: 600;
            color: #374151;
            width: 35%;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }
        @media print {
            .header { background: #0D8ABC !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        @media only screen and (max-width: 480px) {
            .body { padding: 20px 15px; }
            table.details th { width: 40%; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            @php $logo = \App\Models\Setting::companyLogoSrc(); @endphp
            @if($logo)
                <img src="{{ $logo }}" alt="{{ \App\Models\Setting::companyName() }}">
            @endif
            <h1>{{ \App\Models\Setting::companyName() }}</h1>
            <p>Asset Management System</p>
        </div>

        <div class="body">
            @yield('content')
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::companyName() }}. All rights reserved.</p>
            <p>
                This is an automated notification from the Asset Management System.
                @if($address = \App\Models\Setting::companyAddress())
                    <br>{{ $address }}
                @endif
                @if($phone = \App\Models\Setting::companyPhone())
                    <br>Phone: {{ $phone }}
                @endif
            </p>
            <p style="margin-top: 10px;">
                <a href="{{ config('app.url') }}">Visit Asset Management System</a>
            </p>
        </div>
    </div>
</body>
</html>
