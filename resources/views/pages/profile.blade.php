@extends('layouts.dashboard')

@section('title', 'My Profile — Thikana')

@section('content')

{{-- Flash Messages --}}
@if (session('success'))
<div class="dash-welcome-banner" id="flash-banner">
  <div class="dash-welcome-banner-content">
    <div class="dash-welcome-banner-icon">✅</div>
    <div class="dash-welcome-banner-text">
      <p>{{ session('success') }}</p>
    </div>
    <button type="button" class="dash-welcome-dismiss" onclick="document.getElementById('flash-banner').style.display='none'">&times;</button>
  </div>
</div>
@endif

@if ($errors->any())
<div class="dash-welcome-banner" style="border-color: #FEB2B2; background: #FFF5F5;" id="error-banner">
  <div class="dash-welcome-banner-content">
    <div class="dash-welcome-banner-icon">⚠️</div>
    <div class="dash-welcome-banner-text">
      <ul style="margin: 0; padding-left: 18px; color: #C53030;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    <button type="button" class="dash-welcome-dismiss" onclick="document.getElementById('error-banner').style.display='none'">&times;</button>
  </div>
</div>
@endif

<div class="dash-welcome-section">
  <div>
    <h1 class="dash-welcome-heading">My Profile</h1>
    <p class="dash-welcome-sub">Update your personal details and account information.</p>
  </div>
</div>

<div class="dash-content-grid" style="grid-template-columns: 1fr;">
  <div class="dash-card" style="max-width: 640px;">
    <div class="dash-card-header"><h3>Personal Information</h3></div>
    <div class="dash-card-body">
      <form method="POST" action="{{ route('dashboard.profile.update') }}" class="register-form">
        @csrf
        @method('PUT')

        <div class="field">
          <label>Full name <span class="req">*</span></label>
          <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
        </div>

        <div class="field">
          <label>Mobile number</label>
          <input type="tel" value="+91 {{ Auth::user()->phone }}" readonly style="background: var(--green-tint); cursor: not-allowed;">
          <div style="font-size: 12px; color: var(--ink-soft); margin-top: 2px;">Mobile number cannot be changed.</div>
        </div>

        <div class="field">
          <label>Email address <span class="optional">(optional)</span></label>
          <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" placeholder="priya@example.com">
        </div>

        <div class="field">
          <label>Complete address <span class="req">*</span></label>
          <textarea name="address" rows="3" required>{{ old('address', Auth::user()->address) }}</textarea>
        </div>

        <div style="margin-top: 20px;">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('styles')
<style>
  .register-form .field { margin-bottom: 18px; display: flex; flex-direction: column; gap: 6px; }
  .register-form label { font-size: 13px; font-weight: 600; color: var(--ink); }
  .register-form label .req { color: #e53e3e; }
  .register-form label .optional { color: var(--ink-soft); font-weight: 400; font-size: 12px; }
  .register-form input[type="text"],
  .register-form input[type="email"],
  .register-form input[type="tel"],
  .register-form textarea {
    width: 100%; padding: 11px 14px; border: 1.5px solid var(--line); border-radius: var(--radius-sm);
    font-size: 14px; font-family: 'Inter', sans-serif; color: var(--ink); background: #fff;
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .register-form input:focus, .register-form textarea:focus {
    outline: none; border-color: var(--green-deep); box-shadow: 0 0 0 3px rgba(15,94,46,0.12);
  }
</style>
@endpush

@endsection
