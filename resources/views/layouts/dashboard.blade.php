<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard — SkopX')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
    @stack('styles')
</head>

<body class="dashboard-body">

    <header class="dash-header">
        <div class="dash-header-inner">
            <div class="dash-header-left">
                <button type="button" class="sidebar-toggle" id="sidebar-toggle" title="Toggle sidebar">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                </button>
                <a class="brand" href="{{ route('home') }}">
                    <div class="brand-mark">T</div>
                    <div class="brand-name">SkopX</div>
                </a>
            </div>
            <div class="dash-header-center">
                <form class="dash-search" action="{{ route('shops.index') }}" method="get">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search shops, rentals, products…" />
                </form>
            </div>
            <div class="dash-header-right">
                <a href="{{ route('home') }}" class="dash-header-link" title="Back to SkopX">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                        <path d="M10 2l8 7h-3v8h-4v-5H9v5H5v-8H2l8-7z" fill="currentColor" />
                    </svg>
                </a>
                <div class="dash-user-pill">
                    <div class="avatar">{{ Auth::user()->initials() }}</div>
                    <span class="dash-user-name">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="dash-layout">
        @include('partials.dashboard-sidebar')
        <main class="dash-main">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const dashLayout = document.querySelector('.dash-layout');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                dashLayout.classList.toggle('sidebar-open');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                dashLayout.classList.remove('sidebar-open');
            });
        }
    </script>
</body>

</html>
