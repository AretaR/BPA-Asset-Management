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
            <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.general') ? 'active' : '' }}">
                <i class="fas fa-building me-2"></i> Company Settings
            </a>
            <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.email') ? 'active' : '' }}">
                <i class="fas fa-envelope me-2"></i> Email Settings
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building me-2"></i> Company Settings
            </div>
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                   value="{{ old('company_name', \App\Models\Setting::companyName()) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="company_email" class="form-label">Company Email</label>
                            <input type="email" class="form-control" id="company_email" name="company_email" 
                                   value="{{ old('company_email', \App\Models\Setting::companyEmail()) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="company_phone" class="form-label">Company Phone</label>
                            <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                   value="{{ old('company_phone', \App\Models\Setting::companyPhone()) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="Pacific/Tarawa" {{ old('timezone', \App\Models\Setting::timezone()) == 'Pacific/Tarawa' ? 'selected' : '' }}>Pacific/Tarawa (UTC+12)</option>
                                <option value="UTC" {{ old('timezone', \App\Models\Setting::timezone()) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Asia/Manila" {{ old('timezone', \App\Models\Setting::timezone()) == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila</option>
                                <option value="America/New_York" {{ old('timezone', \App\Models\Setting::timezone()) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                <option value="Europe/London" {{ old('timezone', \App\Models\Setting::timezone()) == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="company_address" class="form-label">Company Address</label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ old('company_address', \App\Models\Setting::companyAddress()) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label for="company_logo" class="form-label">Company Logo</label>
                            @if($logo = \App\Models\Setting::companyLogoSrc())
                                <img src="{{ $logo }}" alt="Logo" style="max-height: 100px;">
                            @endif
                            <input type="file" class="form-control @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" accept="image/*">
                            @error('company_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to keep current logo. Max size: 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection