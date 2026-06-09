@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-cog me-2"></i> Settings
    </h1>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="list-group">
            <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-building me-2"></i> Company Settings
            </a>
            <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-envelope me-2"></i> Email Settings
            </a>
            <a href="{{ route('notification-templates.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-bell me-2"></i> Notification Templates
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cog me-2"></i> System Settings
            </div>
            <div class="card-body">
                <p class="text-muted">Select a settings category from the menu to configure the system.</p>
                <div class="list-group">
                    <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-building me-2"></i> Company Settings
                        <small class="text-muted d-block">Configure company information and branding</small>
                    </a>
            <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-envelope me-2"></i> Email Settings
                <small class="text-muted d-block">Configure email notifications, test email delivery</small>
            </a>
            <a href="{{ route('email-logs.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-history me-2"></i> Email Logs
                <small class="text-muted d-block">View sent and failed email delivery logs</small>
            </a>
            <a href="{{ route('email-health.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-heartbeat me-2"></i> Email Health
                <small class="text-muted d-block">Monitor email system health and statistics</small>
            </a>
            <a href="{{ route('notification-templates.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-bell me-2"></i> Notification Templates
                <small class="text-muted d-block">Create and manage custom email notification templates</small>
            </a>
                    @if(auth()->user()->canManageRbac())
                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-shield me-2"></i> Role Management
                        <small class="text-muted d-block">Manage roles and assign permissions</small>
                    </a>
                    <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Permission Management
                        <small class="text-muted d-block">Manage available permissions for RBAC</small>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
