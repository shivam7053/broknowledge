@extends('layouts.app')

@section('title', $post->title)

@section('content')
<article class="container py-5" style="max-width: 800px;">
    <header class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                {{ $post->category->title }}
            </span>
            <time class="text-muted small">{{ $post->created_at->format('F j, Y') }}</time>
        </div>
        <h1 class="display-4 fw-bold text-dark">
            {{ $post->title }}
        </h1>
    </header>

    <div class="blog-content lead text-dark">
        {!! htmlspecialchars_decode($post->html_content) !!}
    </div>

    <footer class="mt-5 pt-4 border-top">
        <a href="{{ route('blog.index') }}" class="btn btn-link text-success text-decoration-none fw-bold p-0">
            ← Back to all posts
        </a>
    </footer>
</article>
@endsection