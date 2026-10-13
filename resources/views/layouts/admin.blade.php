<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal — SKOP-X')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/skopx-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --nav-bg: #0f172a;
            --nav-border: #1e293b;
            --nav-link: #94a3b8;
            --nav-link-hover: #ffffff;
            --nav-link-active: #ffffff;
            --nav-active-bg: #1e293b;
            --primary: #2563eb;
            --primary-accent: #3b82f6;
            --bg-base: #f8fafc;
            --bg-card: #ffffff;
            --card-border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --success: #16a34a;
            --warning: #ea580c;
            --purple: #7c3aed;
            --cyan: #0891b2;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        /* Top Horizontal Bar */
        .admin-horizontal-nav {
            background: var(--nav-bg);
            border-bottom: 1px solid var(--nav-border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .horizontal-nav-inner {
            max-width: 1440px;
            margin: 0 auto;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Brand Left */
        .nav-brand-area {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .nav-brand-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
            background: #ffffff;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .nav-brand-badge {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.4);
            color: #93c5fd;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            letter-spacing: 0.6px;
        }

        /* Center Horizontal Menu Items */
        .horizontal-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            flex-wrap: wrap;
        }

        .horizontal-item {
            display: flex;
            align-items: center;
        }

        .horizontal-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            color: var(--nav-link);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .horizontal-link:hover {
            color: var(--nav-link-hover);
            background: rgba(255, 255, 255, 0.08);
        }

        .horizontal-link.active {
            color: var(--nav-link-active);
            background: var(--nav-active-bg);
            border: 1px solid rgba(59, 130, 246, 0.3);
            font-weight: 600;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        }

        .horizontal-link .menu-icon {
            font-size: 16px;
        }

        .horizontal-badge {
            background: rgba(37, 99, 235, 0.4);
            color: #bfdbfe;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            margin-left: 4px;
        }

        /* Settings Dropdown Menu */
        .nav-dropdown-wrapper {
            position: relative;
        }

        .dropdown-chevron {
            font-size: 10px;
            opacity: 0.7;
            margin-left: 2px;
            transition: transform 0.2s ease;
        }

        .nav-dropdown-wrapper:hover .dropdown-chevron {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 270px;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all 0.2s ease;
            z-index: 1000;
        }

        .nav-dropdown-wrapper:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 6px 10px 4px 10px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 7px;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .dropdown-item.active {
            background: rgba(37, 99, 235, 0.25);
            color: #93c5fd;
        }

        .dropdown-item-icon {
            font-size: 17px;
            flex-shrink: 0;
        }

        .dropdown-item-title {
            font-weight: 600;
            color: inherit;
            line-height: 1.2;
        }

        .dropdown-item-desc {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Right User & Actions */
        .nav-right-area {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .admin-profile-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #cbd5e1;
        }

        .admin-profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-nav-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            font-size: 12.5px;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-nav-logout:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* Sub-Header / Breadcrumb */
        .admin-subbar {
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 12px 24px;
        }

        .subbar-inner {
            max-width: 1440px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .subbar-breadcrumb {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .subbar-date {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Main Page Content */
        .admin-layout-content {
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 26px 24px 60px 24px;
        }

        @media (max-width: 768px) {
            .horizontal-nav-inner {
                padding: 10px 16px;
                gap: 12px;
            }

            .horizontal-menu {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .admin-layout-content {
                padding: 18px 16px 40px 16px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Top Horizontal Navigation Bar -->
    <header class="admin-horizontal-nav">
        <div class="horizontal-nav-inner">

            <!-- Brand Left -->
            <div class="nav-brand-area">
                <img src="{{ asset('images/skopx-logo.png') }}" alt="SKOP-X Logo" class="nav-brand-logo">
                <span class="nav-brand-badge">Admin Portal</span>
            </div>

            <!-- Horizontal Navigation Links -->
            <ul class="horizontal-menu">
                <li class="horizontal-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="horizontal-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">📊</span>
                        <span>Dashboard Overview</span>
                    </a>
                </li>

                <li class="horizontal-item">
                    <a href="{{ route('admin.referrals') }}"
                        class="horizontal-link {{ request()->routeIs('admin.referrals*') ? 'active' : '' }}">
                        <span class="menu-icon">🤝</span>
                        <span>Total Referral List</span>
                        <span
                            class="horizontal-badge">{{ \App\Models\User::whereNotNull('referred_by_id')->count() }}</span>
                    </a>
                </li>

                <li class="horizontal-item">
                    <a href="{{ route('admin.genealogy') }}"
                        class="horizontal-link {{ request()->routeIs('admin.genealogy*') ? 'active' : '' }}">
                        <span class="menu-icon">🌳</span>
                        <span>Genealogy Tree</span>
                    </a>
                </li>

                <li class="horizontal-item nav-dropdown-wrapper">
                    <a href="{{ route('admin.sliders.index') }}"
                        class="horizontal-link {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                        <span class="menu-icon">⚙️</span>
                        <span>Settings</span>
                        <span class="dropdown-chevron">▾</span>
                    </a>
                    <div class="nav-dropdown-menu">
                        <div class="dropdown-header">Website Configuration</div>
                        <a href="{{ route('admin.sliders.index') }}"
                            class="dropdown-item {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                            <span class="dropdown-item-icon">🖼️</span>
                            <div>
                                <div class="dropdown-item-title">Landing Page Sliders</div>
                                <div class="dropdown-item-desc">Upload, edit & remove hero slides</div>
                            </div>
                        </a>
                    </div>
                </li>

                <li class="horizontal-item">
                    <a href="{{ route('home') }}" target="_blank" class="horizontal-link">
                        <span class="menu-icon">🌐</span>
                        <span>Public Website ↗</span>
                    </a>
                </li>

            </ul>

            <!-- Right Profile & Logout -->
            <div class="nav-right-area">
                <div class="admin-profile-pill">
                    <div class="admin-profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-nav-logout">
                        🚪 Logout
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- Sub-bar (Breadcrumbs & Date) -->
    <section class="admin-subbar">
        <div class="subbar-inner">
            <div class="subbar-breadcrumb">
                <span>🛡️</span>
                <span>@yield('breadcrumb', 'Admin Console')</span>
            </div>
            <div class="subbar-date">
                📅 {{ now()->format('l, d M Y') }}
            </div>
        </div>
    </section>

    <!-- Main Page Content -->
    <main class="admin-layout-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
