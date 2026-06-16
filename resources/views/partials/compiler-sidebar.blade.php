{{--
    resources/views/partials/compiler-sidebar.blade.php
    Expects: $activeLang  e.g. 'python'
--}}
<nav class="cp-sidebar" aria-label="Compiler languages">
    <span class="cp-sidebar-label">Languages</span>

    @php
    $langs = [
        ['route' => 'compiler.html',   'dot' => 'dot-html', 'label' => 'HTML / CSS / JS', 'key' => 'html'],
        ['route' => 'compiler.python', 'dot' => 'dot-py',   'label' => 'Python 3.11',     'key' => 'python'],
        ['route' => 'compiler.cpp',    'dot' => 'dot-cpp',  'label' => 'C++ (GCC 13)',    'key' => 'cpp'],
        ['route' => 'compiler.java',   'dot' => 'dot-java', 'label' => 'Java 21',         'key' => 'java'],
        ['route' => 'compiler.php',    'dot' => 'dot-php',  'label' => 'PHP 8.2',         'key' => 'php'],
    ];
    @endphp

    @foreach($langs as $l)
    <a href="{{ route($l['route']) }}"
       class="cp-nav-link {{ ($activeLang ?? '') === $l['key'] ? 'active' : '' }}">
        <span class="cp-dot {{ $l['dot'] }}"></span>
        {{ $l['label'] }}
    </a>
    @endforeach

    <div class="cp-status">
        <span class="status-dot" :class="status"></span>
        <span x-text="{
            online:   'Sandbox online',
            offline:  'Sandbox offline',
            checking: 'Checking…'
        }[status]"></span>
    </div>
</nav>