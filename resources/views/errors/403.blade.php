@extends('layouts.app')

@section('title', '403 - Access Denied')

@section('content')
<div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 text-center reveal animate-float" style="max-width: 600px;">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-shield-lock fs-1"></i>
        </div>
        <div class="eyebrow mb-3">Error 403</div>
        <h1 class="display-4 fw-bold mb-3" style="font-family: var(--font-display);">Access Forbidden</h1>
        <p class="lead text-muted mb-5">
            Stop right there! You don't have the clearance levels to enter this specific chamber of knowledge.
        </p>
        <a href="/" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm transition-all">Return to Safety</a>
    </div>
</div>
@endsection