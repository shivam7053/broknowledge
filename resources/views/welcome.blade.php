@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
<div class="container py-5">
    <div class="bg-light rounded-5 p-5 text-center mb-5 border shadow-sm">
        <h1 class="display-4 fw-bold text-dark mb-3">
            Course <span class="text-success">Catalog</span>
        </h1>
        <p class="lead text-secondary mx-auto" style="max-width: 600px;">
            Deepen your expertise with our structured learning paths. From fundamentals to advanced architecture.
        </p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($courses as $course)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 overflow-hidden">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 44px; height: 44px;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-2">{{ $course->title }}</h3>
                    <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $course->description ?? 'No description available for this course yet.' }}
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('courses.show', $course->slug) }}" class="text-success text-decoration-none fw-bold small">
                            Start Learning &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
