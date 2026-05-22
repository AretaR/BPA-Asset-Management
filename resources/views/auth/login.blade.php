@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-side text-white d-none d-lg-flex flex-column justify-content-center align-items-center">
        <div class="ocean-bg"></div>
        <div class="sun"></div>
        <div class="island"></div>
        <div class="waves"></div>
        <div class="side-content text-center px-5">
            <div class="mb-4">
                <i class="fas fa-satellite-dish fa-4x text-white opacity-75"></i>
            </div>
            <h1 class="display-5 fw-bold mb-4">Welcome to BPA Asset Management</h1>
            <p class="lead text-white-50">Empowering Radio Kiribati with efficient asset tracking and management across the islands.</p>
        </div>
    </div>
    <div class="login-form-side d-flex align-items-center justify-content-center">
        <div class="login-box w-100 p-4 p-sm-5">
            <div class="text-center mb-5">
                @if($logo = \App\Models\Setting::companyLogoSrc())
                    <img src="{{ $logo }}" alt="Logo" class="login-logo mb-4">
                @else
                    <div class="logo-placeholder mb-4 mx-auto d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                @endif
                <h2 class="fw-bolder text-dark mb-1">{{ \App\Models\Setting::companyName() }}</h2>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="needs-validation">
                @csrf
                <div class="form-floating mb-4">
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    <label for="email"><i class="fas fa-envelope text-muted me-2"></i>Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock text-muted me-2"></i>Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none small fw-semibold">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-4 shadow-sm fw-bold">
                    Sign In
                </button>
            </form>

            <div class="text-center">
                <div class="p-3 bg-light rounded-3 border">
                    <p class="mb-1 text-muted small fw-semibold">Default credentials:</p>
                    <code class="text-dark">admin@bpa.com / password</code>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}
.login-wrapper {
    min-height: 100vh;
    display: flex;
    flex-wrap: wrap;
    margin: -1.5rem; /* Offset app.blade.php container padding if any */
}
.login-side {
    flex: 1 1 50%;
    background: linear-gradient(180deg, #0c4a6e 0%, #0ea5e9 30%, #06b6d4 55%, #0891b2 75%, #0d9488 100%);
    position: relative;
    overflow: hidden;
}
.ocean-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.15) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 30%, rgba(255,255,255,0.1) 0%, transparent 40%),
        radial-gradient(ellipse at 50% 80%, rgba(0,0,0,0.15) 0%, transparent 50%);
}
.sun {
    position: absolute;
    top: 8%;
    right: 15%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, #fbbf24 0%, #f59e0b 40%, transparent 70%);
    border-radius: 50%;
    box-shadow: 0 0 60px rgba(251, 191, 36, 0.4), 0 0 120px rgba(251, 191, 36, 0.2);
    z-index: 2;
}
.island {
    position: absolute;
    bottom: 8%;
    left: 10%;
    width: 200px;
    height: 60px;
    background:
        radial-gradient(ellipse at 30% 50%, #65a30d 0%, #4d7c0f 50%, transparent 70%),
        radial-gradient(ellipse at 70% 60%, #a3e635 0%, #84cc16 40%, transparent 60%);
    border-radius: 50%;
    z-index: 3;
    opacity: 0.6;
}
.island::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-bottom: 40px solid #4d7c0f;
    opacity: 0.5;
}
.island::after {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 35%;
    width: 2px;
    height: 25px;
    background: #4d7c0f;
    opacity: 0.3;
    transform: rotate(-15deg);
}
.waves {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 120px;
    z-index: 3;
}
.waves::before,
.waves::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: -50%;
    width: 200%;
    height: 100%;
    border-radius: 40%;
    opacity: 0.3;
    animation: wave 8s linear infinite;
}
.waves::before {
    background: rgba(255,255,255,0.15);
    animation-duration: 8s;
}
.waves::after {
    background: rgba(255,255,255,0.1);
    animation-duration: 12s;
    animation-delay: -4s;
}
@keyframes wave {
    0% { transform: translateX(0) translateY(0) rotate(0deg); }
    25% { transform: translateX(25%) translateY(-5px) rotate(2deg); }
    50% { transform: translateX(50%) translateY(0) rotate(0deg); }
    75% { transform: translateX(25%) translateY(3px) rotate(-2deg); }
    100% { transform: translateX(0) translateY(0) rotate(0deg); }
}
.side-content {
    position: relative;
    z-index: 4;
    max-width: 80%;
}
.login-form-side {
    flex: 1 1 50%;
    background: #ffffff;
}
.login-box {
    max-width: 500px;
}
.login-logo {
    max-height: 60px;
    width: auto;
    object-fit: contain;
}
.login-logo:hover {
    transform: scale(1.05);
}
.logo-placeholder {
    width: 80px;
    height: 80px;
}
.form-floating > .form-control {
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: none;
    transition: all 0.2s ease;
}
.form-floating > .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}
.form-floating > label {
    color: #64748b;
}
.btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}
.btn-primary:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
}
@media (max-width: 991.98px) {
    .login-form-side {
        flex: 1 1 100%;
    }
}
</style>
@endsection