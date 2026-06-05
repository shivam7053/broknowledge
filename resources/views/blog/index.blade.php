@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h1 class="display-4 fw-bold text-dark mb-3">Latest Articles</h1>
        <p class="lead text-secondary">Insights, news, and technical deep dives.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($posts as $post)
            <div class="col">
                <article class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-success bg-opacity-10 text-success text-uppercase small" style="font-size: 0.7rem;">
                                {{ $post->category->title }}
                            </span>
                            <small class="text-muted small">{{ $post->created_at->format('M d, Y') }}</small>
                        </div>
                        <h2 class="card-title h4 fw-bold mb-3">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p class="card-text text-muted small mb-0">
                            {!! Str::limit(strip_tags(htmlspecialchars_decode($post->html_content)), 150) !!}
                        </p>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No articles published yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection