@extends('layouts.app')

@section('title', 'SKOP-X Courses')

@push('styles')
    <style>
        .course-catalog {
            max-width: 1180px;
            margin: 36px auto 64px;
            padding: 0 20px;
        }

        .course-catalog-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--line);
        }

        .course-catalog-heading h1 {
            margin-bottom: 6px;
            color: var(--ink);
            font-family: var(--font-heading, 'Fraunces', serif);
            font-size: 32px;
        }

        .course-catalog-heading p {
            color: var(--ink-soft);
            font-size: 15px;
        }

        .course-catalog-count {
            flex-shrink: 0;
            color: var(--green-deep);
            font-size: 14px;
            font-weight: 700;
        }

        .course-catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 290px), 1fr));
            gap: 20px;
        }

        .course-catalog-card {
            display: block;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            color: inherit;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .course-catalog-card:hover {
            transform: translateY(-2px);
            border-color: var(--green-deep);
            box-shadow: 0 8px 20px rgba(15, 94, 46, 0.1);
        }

        .course-catalog-card:focus-visible {
            outline: 3px solid var(--green-deep);
            outline-offset: 3px;
        }

        .course-catalog-thumb {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #e7eee9;
        }

        .course-catalog-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .course-catalog-video-count {
            position: absolute;
            right: 10px;
            bottom: 10px;
            padding: 5px 8px;
            border-radius: 4px;
            background: rgba(17, 24, 20, 0.84);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
        }

        .course-catalog-content {
            padding: 18px;
        }

        .course-catalog-content h2 {
            margin-bottom: 8px;
            color: var(--ink);
            font-size: 18px;
            line-height: 1.35;
        }

        .course-catalog-content p {
            color: var(--ink-soft);
            font-size: 14px;
            line-height: 1.55;
        }

        .course-catalog-empty {
            padding: 36px 20px;
            border: 1px dashed var(--line);
            border-radius: 8px;
            color: var(--ink-soft);
            text-align: center;
        }

        @media (max-width: 600px) {
            .course-catalog {
                margin-top: 24px;
            }

            .course-catalog-heading {
                align-items: start;
                flex-direction: column;
                gap: 10px;
            }

            .course-catalog-heading h1 {
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="course-catalog">
        <header class="course-catalog-heading">
            <div>
                <h1>Explore SKOP-X Courses</h1>
                <p>Browse practical courses and their video lessons.</p>
            </div>
            <div class="course-catalog-count">{{ $courses->count() }} courses</div>
        </header>

        @if ($courses->isEmpty())
            <div class="course-catalog-empty">Courses will appear here when they are available.</div>
        @else
            <div class="course-catalog-grid">
                @foreach ($courses as $course)
                    <a href="{{ route('register') }}" class="course-catalog-card"
                        aria-label="Register for {{ $course->title }}">
                        <div class="course-catalog-thumb">
                            <img src="{{ $course->catalog_thumbnail ?: asset('images/hero-female-learner.jpg') }}"
                                alt="Video thumbnail for {{ $course->title }}" loading="lazy">
                            <span class="course-catalog-video-count">{{ $course->activeVideos->count() }} videos</span>
                        </div>
                        <div class="course-catalog-content">
                            <h2>{{ $course->title }}</h2>
                            <p>{{ $course->description ?: 'Explore the video lessons in this course.' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection