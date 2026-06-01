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
            <div class="col-lg-7 d-flex align-items-center p-4 p-sm-5 login-form-side" style="background: #171f33;">
                <div class="w-100 px-md-4">
                    <div class="text-center mb-5">
                        @if($logo = \App\Models\Setting::companyLogoSrc())
                            <img src="{{ $logo }}" alt="Logo" class="login-logo mb-4">
                        @else
                            <div class="logo-placeholder mb-4 mx-auto d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="background: #38bdf8; color: #00354a; width: 70px; height: 70px;">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        @endif
                        <h3 class="fw-bold mb-2" style="color: #f8fafc;">Welcome Back</h3>
                        <p class="small" style="color: #94a3b8;">Please sign in to access your dashboard</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="needs-validation auth-form">
                        @csrf
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                            <label for="email"><i class="fas fa-envelope me-2" style="color: #6b7280;"></i>Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock me-2" style="color: #6b7280;"></i>Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check custom-checkbox">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 mb-3 btn-login position-relative overflow-hidden">
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
    background: #0b1326;
    border-radius: 24px;
    overflow: hidden;
    width: 100%;
    max-width: 1000px;
    min-height: 600px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    border: 1px solid #1e293b;
    display: flex;
    flex-direction: column;
}
.login-brand-side {
    background: linear-gradient(135deg, #060e20 0%, #0f172a 100%);
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
.form-floating > .form-control {
    height: 56px;
    padding: 16px 14px 8px;
    border: 1.5px solid #4b5563;
    border-radius: 8px;
    background: #1e293b;
    color: #f8fafc;
    font-size: 15px;
    transition: all 0.2s ease;
}
.form-floating > .form-control:focus {
    border-color: #38bdf8;
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12);
    background: #1e293b;
}
.form-floating > .form-control::placeholder {
    color: transparent;
}
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    transform: scale(0.82) translateY(-0.6rem) translateX(0.1rem);
    color: #38bdf8;
    opacity: 1;
    font-weight: 600;
}
.form-floating > label {
    padding: 14px 14px;
    color: #94a3b8;
    font-size: 14px;
}
.form-floating > .form-control.is-invalid {
    border-color: #ef4444;
}
.form-floating > .form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
}
.btn-login {
    background: #38bdf8;
    border: none;
    transition: all 0.3s ease;
    padding: 14px 24px;
    border-radius: 9999px;
    font-weight: 700;
    font-size: 15px;
    color: #00354a;
}
.btn-login:hover {
    background: #7dd3fc;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(56, 189, 248, 0.2) !important;
    color: #00354a;
}
.custom-checkbox .form-check-input:checked {
    background-color: #38bdf8;
    border-color: #38bdf8;
}
.custom-checkbox .form-check-input {
    border-color: #4b5563;
    background: #1e293b;
}
.form-check-label {
    color: #94a3b8;
    font-size: 13px;
}
.forgot-link {
    color: #38bdf8 !important;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.2s;
}
.forgot-link:hover {
    opacity: 0.7;
    text-decoration: underline;
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