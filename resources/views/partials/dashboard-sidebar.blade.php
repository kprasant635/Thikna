<div class="sidebar-overlay" id="sidebar-overlay"></div>
<aside class="dash-sidebar" id="dash-sidebar">
  <nav class="sidebar-nav">
    <div class="sidebar-section">
      <div class="sidebar-section-label">Menu</div>
      <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sidebar-icon">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="2" fill="currentColor"/><rect x="11" y="2" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="2" y="11" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="11" y="11" width="7" height="7" rx="2" fill="currentColor" opacity=".3"/></svg>
        </span>
        Dashboard
      </a>
      <a href="{{ route('dashboard.profile') }}" class="sidebar-link {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
        <span class="sidebar-icon">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="4" fill="currentColor"/><path d="M3 18c0-3.866 3.134-7 7-7s7 3.134 7 7" fill="currentColor" opacity=".4"/></svg>
        </span>
        My Profile
      </a>
      <a href="{{ route('courses.index') }}" class="sidebar-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
        <span class="sidebar-icon">🎓</span>
        My Courses
      </a>
      <a href="{{ route('certificate.show') }}" class="sidebar-link {{ request()->routeIs('certificate.*') ? 'active' : '' }}">
        <span class="sidebar-icon">🏆</span>
        My Certificate
      </a>
      <a href="{{ route('benefits.index') }}" class="sidebar-link {{ request()->routeIs('benefits.*') ? 'active' : '' }}">
        <span class="sidebar-icon">🌟</span>
        Member Benefits
      </a>
      <a href="{{ route('business.create') }}" class="sidebar-link {{ request()->routeIs('business.create') ? 'active' : '' }}">
        <span class="sidebar-icon">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="3" y="6" width="14" height="11" rx="2" fill="currentColor" opacity=".4"/><path d="M7 6V4a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.5" fill="none"/><circle cx="10" cy="11" r="2" fill="currentColor"/></svg>
        </span>
        Add Listing
      </a>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Explore</div>
      <a href="{{ route('shops.index') }}" class="sidebar-link {{ request()->routeIs('shops.*') ? 'active' : '' }}">
        <span class="sidebar-icon">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="3" width="16" height="14" rx="3" fill="currentColor" opacity=".4"/><path d="M6 7h8M6 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </span>
        Shop Directory
      </a>
      <a href="{{ route('rentals.index') }}" class="sidebar-link {{ request()->routeIs('rentals.*') ? 'active' : '' }}">
        <span class="sidebar-icon">
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2l8 7h-3v8H5v-8H2l8-7z" fill="currentColor" opacity=".5"/><rect x="8" y="12" width="4" height="5" rx="1" fill="currentColor"/></svg>
        </span>
        Home Rentals
      </a>
    </div>

    <div class="sidebar-section sidebar-bottom-section">
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="sidebar-link sidebar-logout-btn">
          <span class="sidebar-icon">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a2 2 0 00-2 2v10a2 2 0 002 2h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M13 14l4-4-4-4M17 10H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
          Log out
        </button>
      </form>
    </div>
  </nav>
</aside>
