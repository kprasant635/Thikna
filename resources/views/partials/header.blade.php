<header class="site-header">
  <div class="wrap header-row1">
    <a class="brand" href="{{ route('home') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;">
      <img src="{{ asset('images/skopx-logo.png') }}" alt="SKOP-X Logo" style="height: 44px; width: auto; object-fit: contain;">
    </a>

    {{-- Search bar --}}
    <form class="header-search" action="{{ route('shops.index') }}" method="get" style="background:#f0f4f8;border-color:#e2e8f0;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Name / Mobile No / ID" style="font-family:'Poppins',sans-serif;" />
      <button type="submit" style="background:#2563eb;border-radius:50%;width:38px;height:38px;padding:0;display:flex;align-items:center;justify-content:center;font-size:16px;">🔍</button>
    </form>

    {{-- Primary navigation right after search bar --}}
    <nav class="primary-nav">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
      @auth
        <a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">SKOP-X Course</a>
      @else
        <a href="#">SKOP-X Course</a>
      @endauth
      <a href="{{ route('register') }}">Join Now</a>
      <a href="#">Achievements</a>
      <a href="#">Support</a>
    </nav>

    {{-- Desktop actions --}}
    <div class="header-actions">
      @guest
        <a class="btn btn-outline sx-login-btn" href="{{ route('login') }}">Login</a>
        <a class="post-btn sx-signup-btn" href="{{ route('register') }}">Sign Up</a>
      @endguest
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-outline sx-login-btn">Dashboard</a>
        <a href="{{ route('dashboard') }}" class="avatar" title="{{ Auth::user()->name }}" style="background:#2563eb;">{{ Auth::user()->initials() }}</a>
      @else
        <span class="avatar sx-avatar-mobile-hide" title="Guest user" style="background:#2563eb;">?</span>
      @endauth
    </div>

    {{-- Mobile hamburger --}}
    <button class="sx-hamburger" id="sx-hamburger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>

  {{-- Mobile nav overlay --}}
  <div class="sx-mobile-nav" id="sx-mobile-nav">
    <div class="sx-mobile-nav-header">
      <div style="display:flex;align-items:center;gap:8px;">
        <img src="{{ asset('images/skopx-logo.png') }}" alt="SKOP-X Logo" style="height: 36px; width: auto; object-fit: contain;">
      </div>
      <button class="sx-mobile-close" onclick="toggleMobileMenu()" aria-label="Close menu">✕</button>
    </div>

    {{-- Mobile search --}}
    <form class="sx-mobile-search" action="{{ route('shops.index') }}" method="get">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Name / Mobile No / ID" />
      <button type="submit">🔍</button>
    </form>

    <nav class="sx-mobile-links">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">🏠 Home</a>
      @auth
        <a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">📚 SKOP-X Course</a>
      @else
        <a href="#">📚 SKOP-X Course</a>
      @endauth
      <a href="{{ route('register') }}">🤝 Join Now</a>
      <a href="#">🏆 Achievements</a>
      <a href="#">🎧 Support</a>
    </nav>

    <div class="sx-mobile-actions">
      @guest
        <a href="{{ route('login') }}" class="sx-mobile-login">Login</a>
        <a href="{{ route('register') }}" class="sx-mobile-signup">Sign Up</a>
      @endguest
      @auth
        <a href="{{ route('dashboard') }}" class="sx-mobile-login">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" style="flex:1;">
          @csrf
          <button type="submit" class="sx-mobile-signup" style="width:100%;cursor:pointer;">Logout</button>
        </form>
      @endauth
    </div>
  </div>
  <div class="sx-mobile-overlay" id="sx-mobile-overlay" onclick="toggleMobileMenu()"></div>
</header>

@push('scripts')
<script>
function toggleMobileMenu() {
  const nav = document.getElementById('sx-mobile-nav');
  const overlay = document.getElementById('sx-mobile-overlay');
  const hamburger = document.getElementById('sx-hamburger');
  const isOpen = nav.classList.contains('open');

  nav.classList.toggle('open');
  overlay.classList.toggle('open');
  hamburger.classList.toggle('open');
  document.body.style.overflow = isOpen ? '' : 'hidden';
}
</script>
@endpush
