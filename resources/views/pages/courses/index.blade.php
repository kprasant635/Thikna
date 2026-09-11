@extends('layouts.app')

@section('title', 'My Educational Courses — Thikana')

@push('styles')
<style>
  .courses-wrapper {
    max-width: 1040px;
    margin: 32px auto 60px;
    padding: 0 20px;
  }
  .courses-hero {
    background: linear-gradient(135deg, var(--green-deep) 0%, #083419 100%);
    color: white;
    border-radius: var(--radius-lg);
    padding: 32px 36px;
    margin-bottom: 32px;
    box-shadow: 0 16px 36px rgba(15, 94, 46, 0.16);
    position: relative;
    overflow: hidden;
  }
  .courses-hero h1 {
    font-family: var(--font-heading, 'Fraunces', serif);
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
  }
  .courses-hero p {
    font-size: 15px;
    opacity: 0.92;
    max-width: 620px;
    line-height: 1.5;
  }
  .progress-card {
    background: white;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-md);
    padding: 24px 28px;
    margin-bottom: 32px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
  }
  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 10px;
  }
  .progress-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--ink);
  }
  .progress-badge {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 700;
  }
  .progress-badge.completed {
    background: #f0fff4;
    color: #276749;
    border: 1px solid #68d391;
  }
  .progress-badge.in-progress {
    background: #fffaf0;
    color: #c05621;
    border: 1px solid #fbd38d;
  }
  .bar-container {
    height: 10px;
    background: #edf2f7;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 12px;
  }
  .bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green-tint) 0%, var(--green-deep) 100%);
    border-radius: 999px;
    transition: width 0.4s ease;
  }

  .courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 22px;
    margin-bottom: 40px;
  }
  .course-card {
    background: white;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-md);
    padding: 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
  }
  .course-card:hover {
    border-color: var(--green-deep);
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15, 94, 46, 0.1);
  }
  .course-category {
    font-size: 12px;
    font-weight: 700;
    color: var(--green-deep);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
  }
  .course-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 8px;
    line-height: 1.3;
  }
  .course-meta {
    font-size: 13.5px;
    color: var(--ink-soft);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .btn-start-course {
    width: 100%;
    padding: 11px;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: 14px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s ease;
  }
  .btn-start-course.completed {
    background: #f0fff4;
    color: #276749;
    border: 1px solid #9ae6b4;
  }
  .btn-start-course.active {
    background: var(--green-deep);
    color: white;
    border: none;
  }
  .btn-start-course.active:hover {
    background: #0b4822;
  }

  .status-cards-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
  }
  .status-box {
    background: white;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-md);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 18px;
  }
  .status-box-icon {
    font-size: 38px;
    width: 64px;
    height: 64px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7fafc;
    flex-shrink: 0;
  }
  .status-box-content h3 {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 4px;
    color: var(--ink);
  }
  .status-box-content p {
    font-size: 13.5px;
    color: var(--ink-soft);
    margin-bottom: 12px;
  }

  @media (max-width: 640px) {
    .status-cards-row {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<div class="courses-wrapper">

  <!-- Hero Header -->
  <div class="courses-hero">
    <h1>Educational Masterclasses 🎓</h1>
    <p>Complete all 3 selected educational courses below to earn your official Thikana Certificate and unlock exclusive member benefits!</p>
  </div>

  @if (session('warning'))
    <div style="background: #fffaf0; border: 1px solid #fbd38d; color: #c05621; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 14px;">
      ⚠️ {{ session('warning') }}
    </div>
  @endif

  @if (session('success'))
    <div style="background: #f0fff4; border: 1px solid #9ae6b4; color: #276749; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 14px;">
      🎉 {{ session('success') }}
    </div>
  @endif

  <!-- Overall Progress Card -->
  <div class="progress-card">
    <div class="progress-header">
      <div class="progress-title">Your Learning Progress</div>
      <div class="progress-badge {{ $is_all_completed ? 'completed' : 'in-progress' }}">
        @if ($is_all_completed)
          ✓ All 3 Courses Completed!
        @else
          {{ $completed_courses }} / {{ $total_courses }} Courses Completed ({{ $overall_percentage }}%)
        @endif
      </div>
    </div>
    <div class="bar-container">
      <div class="bar-fill" style="width: {{ $overall_percentage }}%;"></div>
    </div>
    <div style="font-size: 13.5px; color: var(--ink-soft);">
      @if ($is_all_completed)
        🎉 Congratulations! You have satisfied all learning requirements. Your certificate is generated below!
      @else
        Watch all short videos inside your 3 selected courses to achieve 100% completion.
      @endif
    </div>
  </div>

  <!-- Selected Courses Grid -->
  <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 18px; color: var(--ink);">My Selected Courses ({{ $courses->count() }})</h2>
  
  <div class="courses-grid">
    @foreach ($courses as $course)
      <div class="course-card">
        <div>
          <div class="course-category">{{ $course->product->category ?? 'General' }}</div>
          <div class="course-title">{{ $course->title }}</div>
          <div class="course-meta">
            <span>📹 {{ $course->total_videos }} Short Videos</span>
            <span>•</span>
            <span>{{ $course->completed_videos }} / {{ $course->total_videos }} Completed</span>
          </div>

          <div class="bar-container" style="height: 6px; margin-bottom: 16px;">
            <div class="bar-fill" style="width: {{ $course->progress_percentage }}%;"></div>
          </div>
        </div>

        <div>
          @if ($course->is_completed)
            <a href="{{ route('courses.show', $course) }}" class="btn-start-course completed">
              ✓ Course Completed (Review)
            </a>
          @else
            <a href="{{ route('courses.show', $course) }}" class="btn-start-course active">
              {{ $course->completed_videos > 0 ? 'Continue Learning →' : 'Start Course →' }}
            </a>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <!-- Certificate & Benefits Unlocking Cards -->
  <div class="status-cards-row">
    <!-- Certificate Box -->
    <div class="status-box">
      <div class="status-box-icon" style="background: {{ $is_all_completed ? '#f0fff4' : '#edf2f7' }}; color: {{ $is_all_completed ? 'var(--green-deep)' : '#a0aec0' }};">
        {{ $is_all_completed ? '🏆' : '🔒' }}
      </div>
      <div class="status-box-content">
        <h3>Thikana Official Certificate</h3>
        @if ($is_all_completed && $certificate)
          <p>Certificate #{{ $certificate->certificate_number }} Issued</p>
          <a href="{{ route('certificate.show') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13.5px; border-radius: var(--radius-sm);">View Certificate →</a>
        @else
          <p>Complete all 3 courses to unlock your official certificate.</p>
          <span style="font-size: 12.5px; color: var(--ink-soft); font-weight: 600;">🔒 Locked</span>
        @endif
      </div>
    </div>

    <!-- Benefits Box -->
    <div class="status-box">
      <div class="status-box-icon" style="background: {{ $user->isCertified() ? '#f0fff4' : '#edf2f7' }}; color: {{ $user->isCertified() ? 'var(--green-deep)' : '#a0aec0' }};">
        {{ $user->isCertified() ? '🌟' : '🔒' }}
      </div>
      <div class="status-box-content">
        <h3>Member Benefits</h3>
        @if ($user->isCertified())
          <p>All Thikana member benefits are fully unlocked!</p>
          <a href="{{ route('benefits.index') }}" class="btn btn-outline" style="padding: 8px 16px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--green-deep); color: var(--green-deep);">Explore Benefits →</a>
        @else
          <p>Complete certification to unlock exclusive member benefits.</p>
          <span style="font-size: 12.5px; color: var(--ink-soft); font-weight: 600;">🔒 Locked</span>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection
