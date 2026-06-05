<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :data-bs-theme="darkMode ? 'dark' : 'light'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BroKnowledge - @yield('title')</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-body border-bottom sticky-top py-3">
        <div class="container">
            <a href="/" class="navbar-brand fs-3 fw-bold text-success">BroKnowledge</a>
            
            <div class="d-flex align-items-center gap-4">
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="btn btn-link text-decoration-none p-0 border-0">
                    <span x-show="!darkMode">🌙</span>
                    <span x-show="darkMode">☀️</span>
                </button>
                <div class="navbar-nav flex-row gap-3">
                    <a href="{{ route('courses.index') }}" class="nav-link fw-semibold">Courses</a>
                    <a href="{{ route('blog.index') }}" class="nav-link fw-semibold">Blogs</a>
                </div>
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>