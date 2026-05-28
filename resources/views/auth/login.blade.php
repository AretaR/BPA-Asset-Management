@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-glass-card shadow-lg">
        <div class="row g-0 h-100">
            <!-- Left Branding Side -->
            <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white login-brand-side">
                <div class="brand-overlay"></div>
                <div class="position-relative z-index-2">
                    <i class="fas fa-cube fa-3x mb-4 text-white opacity-75"></i>
                    <h2 class="fw-bold display-6 mb-3">BPA Asset Management</h2>
                    <p class="lead opacity-75 fs-6 lh-lg">An exclusive, centralized portal for authorized personnel to seamlessly track, manage, and optimize organizational resources.</p>
                </div>

            </div>
            
            <!-- Right Form Side -->
            <div class="col-lg-7 d-flex align-items-center p-4 p-sm-5 bg-white login-form-side">
                <div class="w-100 px-md-4">
                    <div class="text-center mb-5">
                        @if($logo = \App\Models\Setting::companyLogoSrc())
                            <img src="{{ $logo }}" alt="Logo" class="login-logo mb-4">
                        @else
                            <div class="logo-placeholder mb-4 mx-auto d-flex align-items-center justify-content-center bg-dark text-white rounded-circle shadow-sm">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        @endif
                        <h3 class="fw-bold text-dark mb-2">Welcome Back</h3>
                        <p class="text-muted small">Please sign in to access your dashboard</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="needs-validation auth-form">
                        @csrf
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control @error('email') is-invalid @enderror custom-input" 
                                   id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                            <label for="email"><i class="fas fa-envelope text-muted me-2"></i>Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control @error('password') is-invalid @enderror custom-input" 
                                   id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock text-muted me-2"></i>Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check custom-checkbox">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label text-muted small" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-primary text-decoration-none small fw-semibold hover-opacity">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 mb-3 shadow-sm rounded-pill fw-bold btn-login position-relative overflow-hidden">
                            <span>Sign In</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Footer -->
    <footer class="login-footer text-center mt-4">
        <p class="mb-0">&copy; {{ date('Y') }} BPA Asset Management System</p>
    </footer>
</div>

<style>
body {
    background: #f0f2f5;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.login-wrapper {
    min-height: 100vh;
    background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1920&auto=format&fit=crop') no-repeat center center;
    background-size: cover;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.login-glass-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 24px;
    overflow: hidden;
    width: 100%;
    max-width: 1000px;
    min-height: 600px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
    display: flex;
    flex-direction: column;
}
.login-brand-side {
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
    position: relative;
    border-radius: 24px 0 0 24px;
}
.brand-overlay {
    display: none;
}
.z-index-2 {
    z-index: 2;
}
.login-form-side {
    border-radius: 0 24px 24px 0;
}
.login-logo {
    max-height: 50px;
    width: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.login-logo:hover {
    transform: scale(1.05);
}
.logo-placeholder {
    width: 70px;
    height: 70px;
}
.custom-input {
    border: none;
    border-bottom: 2px solid #e2e8f0;
    border-radius: 0;
    background: transparent;
    padding-left: 0;
    box-shadow: none !important;
    transition: all 0.3s ease;
}
.custom-input:focus {
    border-bottom-color: #0f172a;
    background: transparent;
}
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    transform: scale(.85) translateY(-1.5rem) translateX(-0.15rem);
    color: #0f172a;
    opacity: 0.8;
}
.form-floating > label {
    padding-left: 0;
    color: #94a3b8;
}
.btn-login {
    background-color: #0f172a;
    border: none;
    transition: all 0.3s ease;
    padding: 14px 24px;
}
.btn-login:hover {
    background-color: #1e293b;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2) !important;
}
.custom-checkbox .form-check-input:checked {
    background-color: #0f172a;
    border-color: #0f172a;
}
.hover-opacity {
    transition: opacity 0.2s;
    color: #0f172a !important;
}
.hover-opacity:hover {
    opacity: 0.7;
}

.login-footer {
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.8rem;
    letter-spacing: 0.3px;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    flex-shrink: 0;
}

@media (max-width: 991.98px) {
    .login-glass-card {
        border-radius: 20px;
    }
    .login-form-side {
        border-radius: 20px;
    }
    .login-wrapper {
        padding: 1rem;
    }
}
</style>
@endsection