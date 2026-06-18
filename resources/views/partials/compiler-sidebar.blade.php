{{--
    resources/views/partials/compiler-sidebar.blade.php
    Expects: $activeLang  e.g. 'python'
--}}
<nav class="cp-sidebar reveal reveal-left" aria-label="Compiler languages">
    <span class="cp-sidebar-label">Languages</span>

    @php
    $langs = [
        ['route' => 'compiler.html',   'dot' => 'dot-html', 'label' => 'HTML / CSS / JS', 'key' => 'html'],
        ['route' => 'compiler.python', 'dot' => 'dot-py',   'label' => 'Python 3.11',     'key' => 'python'],
        ['route' => 'compiler.cpp',    'dot' => 'dot-cpp',  'label' => 'C++ (GCC 13)',    'key' => 'cpp'],
        ['route' => 'compiler.java',   'dot' => 'dot-java', 'label' => 'Java 21',         'key' => 'java'],
        ['route' => 'compiler.php',    'dot' => 'dot-php',  'label' => 'PHP 8.3',         'key' => 'php'],
    ];
    @endphp

    @foreach($langs as $l)
    <a href="{{ route($l['route']) }}"
       class="cp-nav-link {{ ($activeLang ?? '') === $l['key'] ? 'active' : '' }}">
        <span class="cp-dot {{ $l['dot'] }}"></span>
        {{ $l['label'] }}
    </a>
    @endforeach

    <div class="cp-status" role="status" aria-live="polite">
        <span class="status-dot"
              :class="{
                  online:   status === 'online',
                  offline:  status === 'offline',
                  checking: status === 'checking'
              }"></span>
        <span x-text="{
            online:   'Sandbox online',
            offline:  'Sandbox unreachable',
            checking: 'Checking sandbox…'
        }[status]"></span>
    </div>
</nav>