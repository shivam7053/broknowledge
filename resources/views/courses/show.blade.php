@extends('layouts.app')

@section('title', ($currentTopic ? $currentTopic->title . ' - ' : '') . $course->title)

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Sidebar -->
        <aside class="col-12 col-md-3 col-xl-2 border-end bg-light py-4">
            <div class="px-3">
                <h6 class="text-uppercase text-muted fw-bold small mb-4">
                    {{ $course->title }} Topics
                </h6>
                <div class="list-group list-group-flush">
                    @foreach($topics as $topic)
                        <a href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $topic->slug]) }}" 
                           class="list-group-item list-group-item-action border-0 rounded {{ $currentTopic && $currentTopic->id === $topic->id ? 'active bg-success' : '' }}">
                            <span class="me-2 text-muted small">{{ $loop->iteration }}.</span>
                            {{ $topic->title }}
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <article class="col-12 col-md-9 col-xl-10 py-5 px-md-5">
            @if($currentTopic)
                <div class="mx-auto" style="max-width: 800px;">
                    <header class="mb-5 border-bottom pb-4">
                        <h1 class="display-5 fw-bold text-dark mb-2">
                            {{ $currentTopic->title }}
                        </h1>
                        <p class="text-muted mb-0">Part of {{ $course->title }}</p>
                    </header>

                    <div class="course-content">
                        {!! htmlspecialchars_decode($currentTopic->html_content) !!}
                    </div>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-secondary">
                    <p class="h4">Welcome to {{ $course->title }}.<br><small>Select a topic from the sidebar to start learning!</small></p>
                </div>
            @endif
        </article>
    </div>
</div>
@endsection