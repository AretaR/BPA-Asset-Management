@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-edit me-2"></i> Edit Permission
    </h1>
    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<form action="{{ route('permissions.update', $permission) }}" method="POST">
    @csrf
    @method('PUT')
    @include('permissions.partials.form')
</form>
@endsection
