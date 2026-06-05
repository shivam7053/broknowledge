@extends('layouts.app')

@section('title', 'Welcome to BroKnowledge')

@section('content')
<div class="py-5">
    <!-- Hero / Catalog Header -->
    <section class="container text-center mb-5">
        <div class="bg-success rounded-5 p-5 text-white shadow-lg overflow-hidden position-relative">
            <div class="position-relative" style="z-index: 10;">
                <h1 class="display-3 fw-bold mb-4">Master Your Craft</h1>
                <h5 class="fw-normal mb-5 text-white-50 mx-auto" style="max-width: 700px;">
                    Join thousands of students learning through our curated technical courses and insightful articles.
                </h5>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#courses" class="btn btn-light btn-lg px-4 py-3 fw-bold text-success rounded-3">Explore Courses</a>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold rounded-3">Read Blog</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="container mb-5 pt-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold h1">Featured Courses</h2>
                <p class="text-muted">Structured paths to elevate your technical skills.</p>
            </div>
            <a href="{{ route('courses.index') }}" class="text-success text-decoration-none fw-bold">
                View all courses &rarr;
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @foreach($courses->take(4) as $course)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 rounded-4 overflow-hidden">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 44px; height: 44px;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">{{ $course->title }}</h3>
                        <p class="text-muted small mb-4">Master the fundamentals and advanced concepts in this comprehensive track.</p>
                        <a href="{{ route('courses.show', $course->slug) }}" class="text-success text-decoration-none fw-bold small">Start Learning &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Blog Section -->
    <section class="container mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold h1">Latest Articles</h2>
                <p class="text-muted">Deep dives, tutorials, and industry news.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-success text-decoration-none fw-bold">
                Read full blog &rarr;
            </a>
        </div>

        <div class="row g-4">
            @foreach($posts->take(4) as $post)
                <div class="col-md-6 col-lg-3">
                    <article class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success bg-opacity-10 text-success text-uppercase small" style="font-size: 0.7rem;">
                                    {{ $post->category->title }}
                                </span>
                                <small class="text-muted small">{{ $post->created_at->format('M d') }}</small>
                            </div>
                            <h3 class="h6 fw-bold mb-3">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {!! strip_tags(htmlspecialchars_decode($post->html_content)) !!}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 px-4 pb-4">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-success text-decoration-none fw-bold small">Read Article &rarr;</a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection