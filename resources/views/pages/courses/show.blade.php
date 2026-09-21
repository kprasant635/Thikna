@extends('layouts.app')

@section('title', $course->title . ' — SkopX')

@push('styles')
    <style>
        .player-layout {
            max-width: 1140px;
            margin: 28px auto 60px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
        }

        .player-main {
            background: white;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .video-container {
            background: #000;
            position: relative;
            aspect-ratio: 16 / 9;
            width: 100%;
        }

        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: contain;
        }

        .player-info {
            padding: 24px;
        }

        .player-category {
            font-size: 12px;
            font-weight: 700;
            color: var(--green-deep);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .player-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 10px;
        }

        .player-desc {
            font-size: 14.5px;
            color: var(--ink-soft);
            line-height: 1.55;
        }

        .playlist-card {
            background: white;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            height: fit-content;
        }

        .playlist-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .playlist-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .playlist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--line);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: var(--ink);
        }

        .playlist-item:hover {
            border-color: var(--green-tint);
            background: #f8fafc;
        }

        .playlist-item.active {
            border-color: var(--green-deep);
            background: #f4faf6;
        }

        .playlist-item-status {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #cbd5e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .playlist-item.completed .playlist-item-status {
            background: var(--green-deep);
            border-color: var(--green-deep);
            color: white;
        }

        .playlist-item-title {
            font-size: 13.5px;
            font-weight: 600;
            line-height: 1.3;
        }

        .playlist-item-duration {
            font-size: 12px;
            color: var(--ink-soft);
            margin-top: 2px;
        }

        /* Completion Modal */
        .completion-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }

        .completion-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .completion-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 36px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 860px) {
            .player-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="player-layout">

        <!-- Main Player Section -->
        <div class="player-main">
            @php
                $currentVideo = $videos->first();
                function getYouTubeEmbedUrl($url)
                {
                    if (
                        preg_match(
                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/',
                            $url,
                            $matches,
                        )
                    ) {
                        return 'https://www.youtube.com/embed/' . $matches[1] . '?enablejsapi=1&rel=0';
                    }
                    return null;
                }
                $embedUrl = $currentVideo ? getYouTubeEmbedUrl($currentVideo->video_url) : null;
            @endphp

            <div class="video-container" id="videoContainer">
                @if ($embedUrl)
                    <iframe id="youtubeIframe" src="{{ $embedUrl }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                @else
                    <video id="shortVideoPlayer" controls preload="metadata" src="{{ $currentVideo->video_url ?? '' }}">
                        Your browser does not support HTML5 video.
                    </video>
                @endif
            </div>

            <div class="player-info">
                <div class="player-category">{{ $course->product->category ?? 'Educational Course' }}</div>
                <h1 id="videoTitle" class="player-title">{{ $currentVideo->title ?? $course->title }}</h1>
                <p id="videoDesc" class="player-desc">{{ $currentVideo->description ?? $course->description }}</p>

                <div
                    style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; flex-wrap: wrap; gap: 10px;">
                    <div id="videoProgressText" style="color: var(--ink-soft); font-weight: 600;">
                        Status:
                        {{ $currentVideo->user_progress && $currentVideo->user_progress->is_completed ? '✓ Completed' : 'In Progress' }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button type="button" id="btnMarkWatched" class="btn btn-outline"
                            style="padding: 6px 14px; font-size: 12.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--green-deep); color: var(--green-deep); font-weight: 700;">
                            ✓ Complete &amp; Save Progress
                        </button>
                        <span style="color: var(--green-deep); font-weight: 700;">Required Watch: 80%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Playlist Sidebar -->
        <div class="playlist-card">
            <div class="playlist-header">
                <span>Course Playlist</span>
                <span style="font-size: 13px; color: var(--ink-soft); font-weight: 500;">{{ $videos->count() }}
                    Videos</span>
            </div>

            <div class="playlist-items">
                @foreach ($videos as $index => $vid)
                    @php
                        $isVidCompleted = $vid->user_progress && $vid->user_progress->is_completed;
                    @endphp
                    <div class="playlist-item {{ $index === 0 ? 'active' : '' }} {{ $isVidCompleted ? 'completed' : '' }}"
                        data-id="{{ $vid->id }}" data-url="{{ $vid->video_url }}" data-title="{{ $vid->title }}"
                        data-desc="{{ $vid->description }}" data-duration="{{ $vid->duration_seconds }}"
                        data-completed="{{ $isVidCompleted ? '1' : '0' }}">
                        <div class="playlist-item-status">
                            {{ $isVidCompleted ? '✓' : $index + 1 }}
                        </div>
                        <div>
                            <div class="playlist-item-title">{{ $vid->title }}</div>
                            <div class="playlist-item-duration">📺 YouTube Video • ⏱️
                                {{ round($vid->duration_seconds / 60, 1) }} mins</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                <a href="{{ route('courses.index') }}" class="btn btn-outline"
                    style="width: 100%; text-align: center; padding: 10px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">
                    ← Back to Courses
                </a>
            </div>
        </div>

    </div>

    <!-- Completion Modal -->
    <div id="completionModal" class="completion-modal">
        <div class="completion-card">
            <div style="font-size: 54px; margin-bottom: 12px;">🎉</div>
            <h2 style="font-size: 24px; color: var(--green-deep); margin-bottom: 8px;">Course Completed!</h2>
            <p id="completionMessage" style="font-size: 14.5px; color: var(--ink-soft); margin-bottom: 24px;">
                Great job! You have watched all required short videos in this course.
            </p>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a id="btnModalCertificate" href="{{ route('certificate.show') }}" class="btn btn-primary"
                    style="padding: 12px; font-size: 15px; font-weight: 700; border-radius: var(--radius-sm); display: none;">
                    🏆 View Official Certificate
                </a>
                <a href="{{ route('courses.index') }}" class="btn btn-outline"
                    style="padding: 11px; font-size: 14px; border-radius: var(--radius-sm); border: 1.5px solid var(--line);">
                    Go to Courses Dashboard →
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoContainer = document.getElementById('videoContainer');
            const items = document.querySelectorAll('.playlist-item');
            const videoTitle = document.getElementById('videoTitle');
            const videoDesc = document.getElementById('videoDesc');
            const videoProgressText = document.getElementById('videoProgressText');
            const btnMarkWatched = document.getElementById('btnMarkWatched');

            const completionModal = document.getElementById('completionModal');
            const completionMessage = document.getElementById('completionMessage');
            const btnModalCertificate = document.getElementById('btnModalCertificate');

            let currentItem = items[0];

            function extractYouTubeId(url) {
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = url.match(regExp);
                return (match && match[2].length === 11) ? match[2] : null;
            }

            function loadVideo(item) {
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                currentItem = item;

                const url = item.getAttribute('data-url');
                const title = item.getAttribute('data-title');
                const desc = item.getAttribute('data-desc');
                const isCompleted = item.getAttribute('data-completed') === '1';

                videoTitle.textContent = title;
                videoDesc.textContent = desc;
                videoProgressText.textContent = isCompleted ? 'Status: ✓ Completed' : 'Status: In Progress';

                const ytId = extractYouTubeId(url);
                if (ytId) {
                    videoContainer.innerHTML =
                        `<iframe id="youtubeIframe" src="https://www.youtube.com/embed/${ytId}?enablejsapi=1&autoplay=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                } else {
                    videoContainer.innerHTML =
                        `<video id="shortVideoPlayer" controls autoplay src="${url}"></video>`;
                }
            }

            items.forEach(item => {
                item.addEventListener('click', function() {
                    loadVideo(this);
                });
            });

            function sendProgress(percent = 100) {
                if (!currentItem) return;

                const videoId = currentItem.getAttribute('data-id');
                const courseId = '{{ $course->id }}';
                const duration = parseInt(currentItem.getAttribute('data-duration') || '120');

                const updateUrl = `{{ url('/courses') }}/${courseId}/videos/${videoId}/progress`;

                fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            watch_time_seconds: Math.floor((duration * percent) / 100),
                            percentage_watched: percent
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.is_video_completed) {
                            currentItem.setAttribute('data-completed', '1');
                            currentItem.classList.add('completed');
                            currentItem.querySelector('.playlist-item-status').textContent = '✓';
                            videoProgressText.textContent = 'Status: ✓ Completed';
                        }

                        if (data.is_all_courses_completed) {
                            btnModalCertificate.style.display = 'inline-block';
                            completionMessage.textContent =
                                '🎉 Congratulations! You have successfully completed all 3 educational courses. Your official SkopX Certificate is ready!';
                            completionModal.classList.add('active');
                        } else if (data.is_course_completed) {
                            completionMessage.textContent =
                                'Awesome! You have completed all videos in this masterclass. Continue to complete your remaining selected courses!';
                            completionModal.classList.add('active');
                        }
                    })
                    .catch(err => console.error('Progress update error:', err));
            }

            btnMarkWatched.addEventListener('click', function() {
                btnMarkWatched.disabled = true;
                btnMarkWatched.textContent = 'Saving Progress…';
                sendProgress(100);
                setTimeout(() => {
                    btnMarkWatched.disabled = false;
                    btnMarkWatched.textContent = '✓ Complete & Save Progress';
                }, 1000);
            });
        });
    </script>
@endpush
