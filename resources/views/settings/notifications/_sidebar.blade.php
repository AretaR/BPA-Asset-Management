<div class="list-group">
    <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action">
        <i class="fas fa-building me-2"></i> Company Settings
    </a>
    <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action">
        <i class="fas fa-envelope me-2"></i> Email Settings
    </a>
    <a href="{{ route('notification-templates.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('notification-templates.*') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i> Notification Templates
    </a>
    <a href="{{ route('email-logs.index') }}" class="list-group-item list-group-item-action">
        <i class="fas fa-history me-2"></i> Email Logs
    </a>
    <a href="{{ route('email-health.index') }}" class="list-group-item list-group-item-action">
        <i class="fas fa-heartbeat me-2"></i> Email Health
    </a>
</div>
