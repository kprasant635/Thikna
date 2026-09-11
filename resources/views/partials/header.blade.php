<header class="site-header">
  <div class="wrap header-row1">
    <a class="brand" href="{{ route('home') }}">
      <div class="brand-mark">T</div>
      <div class="brand-name">Thikana</div>
    </a>
    <form class="header-search" action="{{ route('shops.index') }}" method="get">
      <div class="city-field">📍 {{ $city ?? 'Guwahati' }}</div>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search shops, rentals, contractors, products…" />
      <button type="submit">Search</button>
    </form>
    <div class="header-actions">
      <button type="button" class="btn btn-outline thm-trigger-btn" style="padding: 8px 14px; font-size: 13px; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px;" title="Customize Theme & Appearance">
        <span>🎨</span>
        <span>Theme</span>
      </button>
      <a class="post-btn" href="{{ route('business.create') }}">+ Post Free Listing</a>
      @guest
        <a class="btn btn-outline" href="/register" style="padding: 9px 16px; margin-left: 2px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">Register</a>
      @endguest
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="padding: 9px 16px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">Dashboard</a>
        <a href="{{ route('dashboard') }}" class="avatar" title="{{ Auth::user()->name }}">{{ Auth::user()->initials() }}</a>
      @else
        <span class="avatar" title="Guest user">?</span>
      @endauth
    </div>
  </div>
  <div class="wrap header-row2">
    <nav class="primary-nav">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">All Categories</a>
      <a href="{{ route('shops.index') }}" class="{{ request()->routeIs('shops.*') ? 'active' : '' }}">Shop Directory</a>
      <a href="{{ route('business.show', ['business' => 'apex-tech-mobile-hub']) }}" class="{{ request()->routeIs('business.*') ? 'active' : '' }}">Products &amp; B2B</a>
      <a href="{{ route('rentals.index') }}" class="{{ request()->routeIs('rentals.*') ? 'active' : '' }}">Home Rentals</a>
    </nav>
    <nav class="secondary-nav">
      <a href="{{ route('category.show', 'automotive') }}">Automotive</a>
      <a href="{{ route('category.show', 'real-estate') }}">Real Estate</a>
      <a href="{{ route('category.show', 'electronics') }}">Electronics</a>
      <a href="{{ route('category.show', 'home-services') }}">Home Services</a>
      <a href="{{ route('category.show', 'b2b-supplies') }}">B2B Supplies</a>
    </nav>
  </div>
</header>
