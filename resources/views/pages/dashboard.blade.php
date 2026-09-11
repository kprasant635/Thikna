@extends('layouts.dashboard')

@section('title', 'Dashboard — Thikana')

@section('content')

{{-- Flash Success / Activation Message --}}
@if (session('success'))
<div class="dash-welcome-banner" id="welcome-banner">
  <div class="dash-welcome-banner-content">
    <div class="dash-welcome-banner-icon">🎉</div>
    <div class="dash-welcome-banner-text">
      <strong>Congratulations!</strong>
      <p>{{ session('success') }}</p>
    </div>
    <button type="button" class="dash-welcome-dismiss" onclick="document.getElementById('welcome-banner').style.display='none'" title="Dismiss">&times;</button>
  </div>
</div>
@endif

{{-- Welcome Header --}}
<div class="dash-welcome-section">
  <div>
    <h1 class="dash-welcome-heading">Welcome to Thikana, {{ Auth::user()->name }} 👋</h1>
    <p class="dash-welcome-sub">You are now an active member. Let's get started!</p>
  </div>
</div>

{{-- Learning & Certification Program Banner --}}
<div class="dash-card" style="margin-bottom: 24px; background: linear-gradient(135deg, #f4faf6 0%, #ffffff 100%); border: 1.5px solid var(--green-tint); padding: 20px 24px;">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="font-size: 12px; font-weight: 700; color: var(--green-deep); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
        🎓 Learning &amp; Certification Program
      </div>
      <h3 style="font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">
        Complete your 3 educational courses to unlock your certificate &amp; benefits
      </h3>
      <p style="font-size: 13.5px; color: var(--ink-soft); margin: 0;">
        Status: <strong style="color: var(--green-deep);">{{ Auth::user()->isCertified() ? '✓ Certified Member' : 'In Progress' }}</strong>
      </p>
    </div>
    <div style="display: flex; gap: 10px;">
      <a href="{{ route('courses.index') }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 13.5px; border-radius: var(--radius-sm);">
        My Courses →
      </a>
      @if (Auth::user()->isCertified())
        <a href="{{ route('certificate.show') }}" class="btn btn-outline" style="padding: 10px 18px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--green-deep); color: var(--green-deep);">
          🏆 Certificate
        </a>
      @endif
    </div>
  </div>
</div>

{{-- Quick Stats Row --}}
<div class="dash-stats-row">
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: var(--green-tint); color: var(--green-deep);">
      <svg width="22" height="22" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="4" fill="currentColor"/><path d="M3 18c0-3.866 3.134-7 7-7s7 3.134 7 7" fill="currentColor" opacity=".4"/></svg>
    </div>
    <div class="dash-stat-info">
      <div class="dash-stat-label">Account Status</div>
      <div class="dash-stat-value" style="color: var(--green-deep);">Active</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: var(--gold-tint); color: var(--gold);">
      <svg width="22" height="22" viewBox="0 0 20 20" fill="none"><rect x="3" y="6" width="14" height="11" rx="2" fill="currentColor" opacity=".5"/><path d="M7 6V4a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
    </div>
    <div class="dash-stat-info">
      <div class="dash-stat-label">My Listings</div>
      <div class="dash-stat-value">0</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #EDE9FE; color: #7C3AED;">
      <svg width="22" height="22" viewBox="0 0 20 20" fill="none"><path d="M10 3l2.09 4.26L17 8.27l-3.5 3.41.83 4.82L10 14.27l-4.33 2.23.83-4.82L3 8.27l4.91-1.01L10 3z" fill="currentColor"/></svg>
    </div>
    <div class="dash-stat-info">
      <div class="dash-stat-label">Your Referral Code</div>
      <div class="dash-stat-value dash-referral-code">{{ Auth::user()->referral_code ?? '—' }}</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #FEE2E2; color: #DC2626;">
      <svg width="22" height="22" viewBox="0 0 20 20" fill="none"><path d="M10 18s-7-5.75-7-10.25C3 4.01 6.13 2 10 2s7 2.01 7 5.75S10 18 10 18z" fill="currentColor"/></svg>
    </div>
    <div class="dash-stat-info">
      <div class="dash-stat-label">Referrals</div>
      <div class="dash-stat-value">{{ Auth::user()->referrals()->count() }}</div>
    </div>
  </div>
</div>

{{-- Main Content Grid --}}
<div class="dash-content-grid">

  {{-- Profile Completion --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3>Profile Completion</h3>
    </div>
    <div class="dash-card-body">
      @php
        $user = Auth::user();
        $filled = collect(['name', 'phone', 'address'])->filter(fn($f) => !empty($user->$f))->count();
        $total = 4;
        if (!empty($user->email)) $filled++;
        $percent = round(($filled / $total) * 100);
      @endphp
      <div class="dash-progress-bar">
        <div class="dash-progress-fill" style="width: {{ $percent }}%;"></div>
      </div>
      <div class="dash-progress-label">{{ $percent }}% complete</div>
      <div class="dash-profile-checklist">
        <div class="dash-check-item {{ $user->name ? 'done' : '' }}">
          <span class="dash-check-icon">{{ $user->name ? '✓' : '○' }}</span> Full name
        </div>
        <div class="dash-check-item {{ $user->phone ? 'done' : '' }}">
          <span class="dash-check-icon">{{ $user->phone ? '✓' : '○' }}</span> Mobile number verified
        </div>
        <div class="dash-check-item {{ $user->email ? 'done' : '' }}">
          <span class="dash-check-icon">{{ $user->email ? '✓' : '○' }}</span> Email address
        </div>
        <div class="dash-check-item {{ $user->address ? 'done' : '' }}">
          <span class="dash-check-icon">{{ $user->address ? '✓' : '○' }}</span> Complete address
        </div>
      </div>
      @if ($percent < 100)
      <a href="{{ route('dashboard.profile') }}" class="btn btn-tint" style="margin-top:14px; display:inline-block;">Complete Profile</a>
      @endif
    </div>
  </div>

  {{-- My Listings --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3>My Listings</h3>
    </div>
    <div class="dash-card-body">
      <div class="dash-empty-state">
        <div class="dash-empty-icon">🏪</div>
        <p>You haven't added any listings yet.</p>
        <a href="{{ route('business.create') }}" class="btn btn-primary" style="margin-top:10px;">+ Add Your First Listing</a>
      </div>
    </div>
  </div>

  {{-- Quick Actions --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3>Quick Actions</h3>
    </div>
    <div class="dash-card-body">
      <div class="dash-quick-actions">
        <a href="{{ route('business.create') }}" class="dash-action-tile">
          <span class="dash-action-icon">📝</span>
          <span>Post a Listing</span>
        </a>
        <a href="{{ route('shops.index') }}" class="dash-action-tile">
          <span class="dash-action-icon">🔍</span>
          <span>Browse Shops</span>
        </a>
        <a href="{{ route('rentals.index') }}" class="dash-action-tile">
          <span class="dash-action-icon">🏠</span>
          <span>Find Rentals</span>
        </a>
        <a href="{{ route('dashboard.profile') }}" class="dash-action-tile">
          <span class="dash-action-icon">👤</span>
          <span>Edit Profile</span>
        </a>
      </div>
    </div>
  </div>

  {{-- Account Information --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3>Account Information</h3>
    </div>
    <div class="dash-card-body">
      <div class="dash-info-grid">
        <div class="dash-info-item">
          <div class="dash-info-label">Full Name</div>
          <div class="dash-info-value">{{ Auth::user()->name }}</div>
        </div>
        <div class="dash-info-item">
          <div class="dash-info-label">Mobile</div>
          <div class="dash-info-value">+91 {{ Auth::user()->phone }}</div>
        </div>
        <div class="dash-info-item">
          <div class="dash-info-label">Email</div>
          <div class="dash-info-value">{{ Auth::user()->email ?? 'Not provided' }}</div>
        </div>
        <div class="dash-info-item">
          <div class="dash-info-label">Member Since</div>
          <div class="dash-info-value">{{ Auth::user()->created_at->format('d M Y') }}</div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
