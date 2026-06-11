@extends('layouts.app')

@section('title', '500 - Server Brain Freeze')

@section('content')
<div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 text-center reveal animate-float" style="max-width: 600px;">
        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-cpu fs-1"></i>
        </div>
        <div class="eyebrow mb-3">Error 500</div>
        <h1 class="display-4 fw-bold mb-3" style="font-family: var(--font-display);">System Overload</h1>
        <p class="lead text-muted mb-5">
            Our servers are having a bit of a "brain freeze". We've been notified and our developers are on it.
        </p>
        <a href="/" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm transition-all">Try Refreshing</a>
    </div>
</div>
@endsection