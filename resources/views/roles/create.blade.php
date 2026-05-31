@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-header mb-0">
        <i class="fas fa-plus-circle me-2"></i> Add New Role
    </h1>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    @include('roles.partials.form', ['role' => null, 'assignedPermissions' => []])
</form>
@endsection
