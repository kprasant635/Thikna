@extends('layouts.app')

@section('title', 'Unlocked Member Benefits — Thikana')

@push('styles')
<style>
  .benefits-wrapper {
    max-width: 1040px;
    margin: 32px auto 60px;
    padding: 0 20px;
  }
  .benefits-hero {
    background: linear-gradient(135deg, #1a365d 0%, #0d213a 100%);
    color: white;
    border-radius: var(--radius-lg);
    padding: 36px 40px;
    margin-bottom: 32px;
    box-shadow: 0 16px 36px rgba(26, 54, 93, 0.2);
    position: relative;
  }
  .benefits-hero h1 {
    font-family: var(--font-heading, 'Fraunces', serif);
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
  }
  .benefits-hero p {
    font-size: 16px;
    opacity: 0.92;
    max-width: 640px;
    line-height: 1.5;
  }

  .benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 22px;
    margin-bottom: 40px;
  }
  .benefit-card {
    background: white;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-md);
    padding: 24px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    transition: all 0.25s ease;
  }
  .benefit-card:hover {
    border-color: var(--green-deep);
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15, 94, 46, 0.1);
  }
  .benefit-tag {
    display: inline-block;
    padding: 4px 12px;
    background: #f0fff4;
    color: #276749;
    border: 1px solid #9ae6b4;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 12px;
  }
  .benefit-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 8px;
  }
  .benefit-desc {
    font-size: 14px;
    color: var(--ink-soft);
    line-height: 1.5;
  }
</style>
@endpush

@section('content')
<div class="benefits-wrapper">

  <!-- Hero Header -->
  <div class="benefits-hero">
    <h1>Exclusive Member Benefits 🌟</h1>
    <p>Congratulations, {{ $user->name }}! As a certified Thikana member, all premium features, badges, priority listings, and VIP tools are now fully unlocked for your account.</p>
  </div>

  <div class="benefits-grid">
    @foreach ($benefits as $benefit)
      <div class="benefit-card">
        <div class="benefit-tag">✓ {{ $benefit['status'] }} • {{ $benefit['category'] }}</div>
        <div class="benefit-title">{{ $benefit['title'] }}</div>
        <div class="benefit-desc">{{ $benefit['description'] }}</div>
      </div>
    @endforeach
  </div>

  <div style="text-align: center; background: white; border: 1.5px solid var(--line); border-radius: var(--radius-md); padding: 32px;">
    <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px; color: var(--ink);">Ready to grow your business on Thikana?</h3>
    <p style="font-size: 14.5px; color: var(--ink-soft); margin-bottom: 20px;">Use your unlocked benefits to create free listings, feature products, or explore directory shops.</p>
    <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
      <a href="{{ route('business.create') }}" class="btn btn-primary" style="padding: 11px 22px; font-size: 14.5px; border-radius: var(--radius-sm);">
        + Post Free Listing
      </a>
      <a href="{{ route('dashboard') }}" class="btn btn-outline" style="padding: 11px 22px; font-size: 14.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">
        Go to Dashboard →
      </a>
    </div>
  </div>

</div>
@endsection
