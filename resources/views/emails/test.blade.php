@extends('emails.layout')

@section('content')
    <h2 style="color: #0D8ABC; margin-top: 0;">Test Email</h2>

    <p>This is a test email from the BPA Asset Management System.</p>

    <p>If you received this email, your email notification system is configured correctly.</p>

    <table class="details">
        <tr>
            <th>System</th>
            <td>BPA Asset Management</td>
        </tr>
        <tr>
            <th>Date/Time</th>
            <td>{{ now()->format('F j, Y g:i A') }}</td>
        </tr>
        <tr>
            <th>Mail Driver</th>
            <td>{{ config('mail.default') }}</td>
        </tr>
        <tr>
            <th>From Address</th>
            <td>{{ config('mail.from.address') }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px; color: #6b7280; font-size: 13px;">
        No action is required. This is an automated test message.
    </p>
@endsection
