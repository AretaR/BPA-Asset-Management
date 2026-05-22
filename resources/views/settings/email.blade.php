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
            <a href="{{ route('settings.email') }}" class="list-group-item list-group-item-action {{ request()->routeIs('settings.email') ? 'active' : '' }}">
                <i class="fas fa-envelope me-2"></i> Email Settings
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-envelope me-2"></i> Email Settings
            </div>
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email_from_address" class="form-label">From Address</label>
                            <input type="email" class="form-control" id="email_from_address" name="email_from_address" 
                                   value="{{ old('email_from_address', \App\Models\Setting::emailFrom()) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="email_from_name" class="form-label">From Name</label>
                            <input type="text" class="form-control" id="email_from_name" name="email_from_name" 
                                   value="{{ old('email_from_name', \App\Models\Setting::emailFromName()) }}">
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> SMTP settings should be configured in the <code>.env</code> file for production use.
                            </div>
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