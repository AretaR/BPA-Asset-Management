<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BPA Asset Management' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @if($logo = \App\Models\Setting::companyLogoSrc())
    <link rel="icon" href="{{ $logo }}" sizes="32x32">
    @else
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>&#x1F3E2;</text></svg>">
    @endif
</head>
<body>
    @auth
    <div class="wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="company-logo">
                    @if($logo = \App\Models\Setting::companyLogoSrc())
                        <img src="{{ $logo }}" alt="Logo">
                    @else
                        <div class="logo-placeholder">
                            <i class="fas fa-building"></i>
                        </div>
                    @endif
                    <div>
                        <div class="company-name">{{ \App\Models\Setting::companyName() }}</div>
                        <div class="company-sub">Asset Management</div>
                    </div>
                </div>
            </div>

            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    @can('viewAny', \App\Models\Asset::class)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                            <i class="fas fa-boxes"></i> Assets
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', \App\Models\Category::class)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', \App\Models\Department::class)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                            <i class="fas fa-building"></i> Departments
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', \App\Models\User::class)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    @endcan
                    @if(auth()->user()->canManageRbac())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield"></i> Roles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                            <i class="fas fa-key"></i> Permissions
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->canViewReports())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermissionTo('scanner.access'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('scanner.*') ? 'active' : '' }}" href="{{ route('scanner.index') }}">
                            <i class="fas fa-qrcode"></i> Scanner
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->canManageSettings())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    @endif

                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-footer-text">&copy; {{ date('Y') }} {{ \App\Models\Setting::companyName() }}</div>
            </div>
        </nav>

        <div class="main-content">
            <nav class="top-navbar">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div class="d-flex align-items-center gap-3">
                        <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="{{ route('dashboard') }}">
                            <i class="fas fa-cubes"></i>
                            <span>{{ \App\Models\Setting::companyName() }}</span>
                        </a>
                    </div>

                    <ul class="navbar-nav ms-auto flex-row align-items-center gap-1">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                                <span class="user-name">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 10px; padding: 6px;">
                                <li><a class="dropdown-item py-2" href="{{ route('users.profile') }}"><i class="fas fa-user me-2 text-muted"></i> Profile</a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2"><i class="fas fa-sign-out-alt me-2 text-muted"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="app-footer">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <span>&copy; {{ date('Y') }} {{ \App\Models\Setting::companyName() }}. All rights reserved.</span>
                    <span>Powered by BPA Asset Management System</span>
                </div>
            </footer>
        </div>
    </div>
    @else
    @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
