@extends('layouts.app')

@section('title', '419 - Session Expired')

@section('content')
<div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 text-center reveal animate-float" style="max-width: 600px;">
        <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-clock-history fs-1"></i>
        </div>
        <div class="eyebrow mb-3">Error 419</div>
        <h1 class="display-4 fw-bold mb-3" style="font-family: var(--font-display);">Session Timed Out</h1>
        <p class="lead text-muted mb-5">
            Your session took a nap. To protect your data, please refresh the page and try your request again.
        </p>
        <button onclick="window.location.reload()" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm transition-all">Refresh Page</button>
    </div>
</div>
@endsection