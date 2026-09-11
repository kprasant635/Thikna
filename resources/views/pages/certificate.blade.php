@extends('layouts.app')

@section('title', 'Official Certificate — Thikana')

@push('styles')
<style>
  .cert-page-wrapper {
    max-width: 960px;
    margin: 32px auto 60px;
    padding: 0 20px;
  }
  .cert-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    background: white;
    padding: 16px 24px;
    border-radius: var(--radius-md);
    border: 1.5px solid var(--line);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    flex-wrap: wrap;
    gap: 12px;
  }
  
  /* Certificate Frame */
  .certificate-card {
    background: #fffdf9;
    border: 12px solid #1a365d;
    border-radius: 8px;
    padding: 50px 60px;
    position: relative;
    box-shadow: 0 20px 50px rgba(0,0,0,0.12);
    text-align: center;
    color: #1a202c;
    overflow: hidden;
  }
  .certificate-card::before {
    content: '';
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
    border: 2px solid #d69e2e;
    pointer-events: none;
  }
  .certificate-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 220px;
    opacity: 0.03;
    pointer-events: none;
    user-select: none;
  }
  
  .cert-header {
    margin-bottom: 30px;
  }
  .cert-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }
  .cert-brand-mark {
    width: 44px;
    height: 44px;
    background: var(--green-deep);
    color: white;
    font-size: 24px;
    font-weight: 800;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .cert-brand-name {
    font-family: var(--font-heading, 'Fraunces', serif);
    font-size: 28px;
    font-weight: 700;
    color: var(--green-deep);
  }

  .cert-title-main {
    font-family: var(--font-heading, 'Fraunces', serif);
    font-size: 34px;
    font-weight: 800;
    color: #1a365d;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .cert-sub-title {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #718096;
    font-weight: 600;
  }

  .cert-body {
    margin: 36px 0;
  }
  .cert-present {
    font-size: 15px;
    color: #4a5568;
    margin-bottom: 16px;
    font-style: italic;
  }
  .cert-user-name {
    font-family: var(--font-heading, 'Fraunces', serif);
    font-size: 36px;
    font-weight: 800;
    color: #0f5e2e;
    border-bottom: 2px solid #d69e2e;
    display: inline-block;
    padding: 0 24px 6px;
    margin-bottom: 20px;
  }
  .cert-statement {
    font-size: 16px;
    color: #2d3748;
    line-height: 1.6;
    max-width: 680px;
    margin: 0 auto 24px;
  }
  .cert-courses-list {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 30px;
  }
  .cert-course-tag {
    background: #fefcbf;
    border: 1px solid #ecc94b;
    color: #744210;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 700;
  }

  .cert-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 50px;
    padding-top: 24px;
    border-top: 1px solid #e2e8f0;
  }
  .cert-seal {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: radial-gradient(circle, #f6e05e 0%, #b7791f 100%);
    border: 3px solid #744210;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  .cert-sig-block {
    text-align: center;
  }
  .cert-sig-line {
    font-family: 'Fraunces', serif;
    font-size: 22px;
    font-weight: 700;
    color: #0f5e2e;
    font-style: italic;
    margin-bottom: 4px;
  }

  @media print {
    .site-header, .site-footer, .cert-actions-bar {
      display: none !important;
    }
    .cert-page-wrapper {
      max-width: 100%;
      margin: 0;
      padding: 0;
    }
    .certificate-card {
      box-shadow: none;
      border-width: 8px;
    }
  }
</style>
@endpush

@section('content')
<div class="cert-page-wrapper">

  <!-- Top Action Bar -->
  <div class="cert-actions-bar">
    <div>
      <span style="font-weight: 700; font-size: 16px; color: var(--ink);">Official Thikana Certificate</span>
      <span style="font-size: 13px; color: var(--ink-soft); margin-left: 10px;">ID: {{ $certificate->certificate_number }}</span>
    </div>
    <div style="display: flex; gap: 10px;">
      <button type="button" onclick="window.print()" class="btn btn-outline" style="padding: 9px 18px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">
        🖨️ Print Certificate
      </button>
      <a href="{{ route('benefits.index') }}" class="btn btn-primary" style="padding: 9px 18px; font-size: 13.5px; border-radius: var(--radius-sm);">
        Explore Member Benefits →
      </a>
    </div>
  </div>

  <!-- Certificate Canvas Card -->
  <div class="certificate-card">
    <div class="certificate-watermark">T</div>

    <div class="cert-header">
      <div class="cert-brand">
        <div class="cert-brand-mark">T</div>
        <div class="cert-brand-name">Thikana</div>
      </div>
      <div class="cert-title-main">Certificate of Completion</div>
      <div class="cert-sub-title">Educational &amp; Skill Development Masterclasses</div>
    </div>

    <div class="cert-body">
      <div class="cert-present">This is to proudly certify that</div>
      <div class="cert-user-name">{{ $user->name }}</div>
      <div class="cert-statement">
        has successfully completed all mandatory short-video educational courses and fulfilled the educational skill requirements for certified Thikana membership.
      </div>

      <div class="cert-courses-list">
        @if (isset($certificate->payload['courses']) && is_array($certificate->payload['courses']))
          @foreach ($certificate->payload['courses'] as $courseTitle)
            <div class="cert-course-tag">✓ {{ $courseTitle }}</div>
          @endforeach
        @else
          <div class="cert-course-tag">✓ Selected Subscription Masterclasses</div>
        @endif
      </div>
    </div>

    <div class="cert-footer">
      <div style="text-align: left; font-size: 13px; color: #718096;">
        <div><strong>Certificate No:</strong> {{ $certificate->certificate_number }}</div>
        <div><strong>Issue Date:</strong> {{ $certificate->issued_at->format('F d, Y') }}</div>
        <div><strong>Verification Status:</strong> Verified &amp; Authentic</div>
      </div>

      <div class="cert-seal">
        🏅
      </div>

      <div class="cert-sig-block">
        <div class="cert-sig-line">Thikana Executive Board</div>
        <div style="font-size: 12.5px; color: #718096; text-transform: uppercase; font-weight: 600;">Authorized Signatory</div>
      </div>
    </div>
  </div>

</div>
@endsection
