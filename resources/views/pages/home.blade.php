@extends('layouts.app')

@section('title', 'SKOP-X — Skill Today, Better Tomorrow')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/skopx.css') }}">
@endpush

@section('content')
    <div class="skopx-page">

        {{-- ====== 1. HERO BANNER ====== --}}
        <section class="sx-hero">
            <div class="sx-container">
                <div class="sx-hero-inner">
                    <div class="sx-course-carousel" data-course-carousel role="region" aria-roledescription="carousel" aria-label="Courses">
                        @forelse ($courses as $course)
                            <article class="sx-course-slide" role="group" aria-roledescription="slide"
                                aria-label="Course {{ $loop->iteration }} of {{ $loop->count }}" @if (!$loop->first) hidden @endif>
                                <div class="sx-course-copy">
                                    <div class="sx-course-eyebrow">SKOP-X COURSE {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                    <h1>{{ $course->title }}</h1>
                                    <p class="sx-course-description">{{ $course->description ?: 'Build practical skills and take your next step with SKOP-X.' }}</p>
                                    <a href="{{ route('register') }}" class="sx-btn-yellow">Join Now <span aria-hidden="true">→</span></a>
                                </div>

                                <div class="sx-hero-img">
                                    <img src="{{ asset('images/hero-female-learner.jpg') }}" alt="Learner studying {{ $course->title }}" @if ($loop->first) loading="eager" @else loading="lazy" @endif>
                                </div>
                            </article>
                        @empty
                            <article class="sx-course-slide sx-course-slide-empty">
                                <div class="sx-course-copy">
                                    <div class="sx-course-eyebrow">SKOP-X LEARNING</div>
                                    <h1>Skill Today<br>Better Tomorrow</h1>
                                    <p class="sx-course-description">Learn practical skills and grow with a supportive community.</p>
                                    <a href="{{ route('register') }}" class="sx-btn-yellow">Join Now <span aria-hidden="true">→</span></a>
                                </div>
                                <div class="sx-hero-img">
                                    <img src="{{ asset('images/hero-female-learner.jpg') }}" alt="SKOP-X learner" loading="eager">
                                </div>
                            </article>
                        @endforelse

                        @if ($courses->count() > 1)
                            <div class="sx-course-controls">
                                <span class="sx-course-count" aria-live="polite"><span data-course-current>01</span> / {{ str_pad((string) $courses->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="sx-course-arrows">
                                    <button type="button" data-course-previous aria-label="Previous course">←</button>
                                    <button type="button" data-course-toggle aria-label="Pause automatic slide rotation" aria-pressed="false">Ⅱ</button>
                                    <button type="button" data-course-next aria-label="Next course">→</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ====== 2. FEATURE CATEGORY STRIP ====== --}}
        <section class="sx-categories">
            <div class="sx-container">
                <div class="sx-categories-inner">
                    @foreach ($categories as $cat)
                        <a class="sx-category-item" href="#">
                            <div class="sx-category-icon" style="background: {{ $cat['color'] }}15;">
                                <span>{{ $cat['icon'] }}</span>
                            </div>
                            <div class="sx-category-label">{{ $cat['title'] }}</div>
                        </a>
                    @endforeach

                    <div class="sx-category-cta">
                        <p><strong>Thousands are learning.</strong><br>Now it's your turn!</p>
                        <a href="{{ route('register') }}" class="sx-btn-blue">Join SKOP-X Today →</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ====== 3. MAIN CONTENT ====== --}}
        <section class="sx-main-content">
            <div class="sx-container">
                <div class="sx-main-grid">

                    {{-- CENTER: Promo Banner + Info Cards --}}
                    <div>
                        <div class="sx-promo-banner">
                            <div class="sx-promo-text">
                                <h2>Learn<br>From Anywhere</h2>
                                <p>Video Courses | Practical Training | Real Opportunities</p>
                                <a href="#" class="sx-btn-orange">Explore Courses →</a>
                            </div>
                            <div class="sx-promo-img">
                                <img src="{{ asset('images/promo-male-learner.jpg') }}" alt="Learn from anywhere">
                                <div class="sx-play-btn"></div>
                            </div>
                        </div>

                        <div class="sx-info-cards">
                            <div class="sx-info-card">
                                <div class="sx-info-card-icon">💡</div>
                                <h4>Why Choose SKOP-X?</h4>
                                <p>Practical learning for real life success.</p>
                            </div>
                            <div class="sx-info-card">
                                <div class="sx-info-card-icon">⚙️</div>
                                <h4>How It Works?</h4>
                                <p>Simple steps to start your journey.</p>
                            </div>
                            <div class="sx-info-card">
                                <div class="sx-info-card-icon">💰</div>
                                <h4>Income Opportunity</h4>
                                <p>Learn, Share & Earn together.</p>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Recent Joinings --}}
                    <div class="sx-joinings-card">
                        <div class="sx-card-header">
                            <h3>Recent New Joinings</h3>
                            <a class="sx-view-all" href="#">View All</a>
                        </div>

                        @foreach ($recentJoinings as $joining)
                            <div class="sx-joining-item">
                                <div class="sx-joining-avatar">
                                    @if (!empty($joining['avatar']))
                                        <img src="{{ asset('storage/' . $joining['avatar']) }}" alt="{{ $joining['name'] }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                        <div class="sx-avatar-placeholder" style="display:none;">
                                            {{ strtoupper(substr($joining['name'], 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="sx-avatar-placeholder">
                                            {{ strtoupper(substr($joining['name'], 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="sx-joining-info">
                                    <div class="sx-joining-name">{{ $joining['name'] }}</div>
                                    <div class="sx-joining-id">ID: {{ $joining['id'] }}</div>
                                    <div class="sx-joining-city">{{ $joining['city'] }}</div>
                                </div>
                                <div class="sx-joining-date">{{ $joining['date'] }}</div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>

        {{-- ====== 4. FEATURED VIDEOS ====== --}}
        <section class="sx-videos-section">
            <div class="sx-container">
                <div class="sx-videos-header">
                    <h2>Featured Videos – Learn, Grow, Earn</h2>
                    <a class="sx-view-all" href="#">View All Videos</a>
                </div>

                <div class="sx-videos-row">
                    @foreach ($featuredVideos as $video)
                        <div class="sx-video-card">
                            <div class="sx-video-thumb">
                                @if (!empty($video['thumbnail']))
                                    <img src="{{ asset('images/' . $video['thumbnail']) }}" alt="" loading="lazy">
                                @endif
                                <div class="sx-video-play"></div>
                                <div class="sx-video-duration">{{ $video['duration'] }}</div>
                            </div>
                            <div class="sx-video-body">
                                <h4>{{ $video['title'] }}</h4>
                                <p>{{ $video['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Small promo card --}}
                    <div class="sx-small-promo">
                        <h4>Small Learning<br>Big Opportunities</h4>
                        <img src="{{ asset('images/growth-plant.jpg') }}" alt="Growth">
                        <a href="{{ route('register') }}" class="sx-btn-blue">Start Learning →</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ====== 5. THREE BOTTOM PANELS ====== --}}
        <section class="sx-bottom-panels">
            <div class="sx-container">
                <div class="sx-panels-grid">

                    {{-- Top Achievers --}}
                    <div class="sx-panel">
                        <div class="sx-card-header">
                            <h3>Top Achievers</h3>
                            <a class="sx-view-all" href="#">View All</a>
                        </div>

                        @foreach ($topAchievers as $i => $achiever)
                            <div class="sx-achiever-item">
                                <div class="sx-achiever-rank">{{ $i + 1 }}</div>
                                <div class="sx-achiever-avatar" style="background: {{ $achiever['color'] }};">
                                    {{ $achiever['avatar'] }}</div>
                                <div class="sx-achiever-info">
                                    <div class="sx-achiever-name">{{ $achiever['name'] }}</div>
                                    <div class="sx-achiever-level" style="color: {{ $achiever['color'] }};">
                                        {{ $achiever['level'] }}</div>
                                </div>
                                <div class="sx-achiever-amount">
                                    <span class="amt">₹ {{ $achiever['amount'] }}</span>
                                    <span class="period">This Month</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Birthday Wishes --}}
                    <div class="sx-panel">
                        <div class="sx-card-header">
                            <h3>Birthday Wishes</h3>
                            <a class="sx-view-all" href="#">View All</a>
                        </div>

                        @foreach ($birthdays as $bday)
                            <div class="sx-birthday-item">
                                <div class="sx-birthday-avatar">🎂</div>
                                <div class="sx-birthday-info">
                                    <div class="sx-birthday-name">{{ $bday['name'] }}</div>
                                    <div class="sx-birthday-msg">{{ $bday['message'] }}</div>
                                </div>
                                <div class="sx-birthday-date">{{ $bday['date'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Marriage Anniversary --}}
                    <div class="sx-panel">
                        <div class="sx-card-header">
                            <h3>Marriage Anniversary</h3>
                            <a class="sx-view-all" href="#">View All</a>
                        </div>

                        @foreach ($anniversaries as $anniv)
                            <div class="sx-anniv-item">
                                <div class="sx-anniv-avatars">
                                    <div class="sx-anniv-avatar" style="background: var(--sx-blue);">
                                        {{ $anniv['avatars'][0] }}</div>
                                    <div class="sx-anniv-avatar" style="background: var(--sx-pink);">
                                        {{ $anniv['avatars'][1] }}</div>
                                </div>
                                <div class="sx-anniv-info">
                                    <div class="sx-anniv-names">{{ $anniv['names'] }}</div>
                                    <div class="sx-anniv-msg">{{ $anniv['message'] }}</div>
                                </div>
                                <div class="sx-anniv-date">{{ $anniv['date'] }}</div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>

    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-course-carousel]').forEach((carousel) => {
                const slides = Array.from(carousel.querySelectorAll('.sx-course-slide[aria-roledescription="slide"]'));
                const currentCount = carousel.querySelector('[data-course-current]');
                const rotationToggle = carousel.querySelector('[data-course-toggle]');

                if (slides.length < 2 || !currentCount) return;

                let activeIndex = 0;
                let rotationTimer;
                let rotationPaused = false;
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                const pauseRotation = () => {
                    window.clearInterval(rotationTimer);
                    rotationTimer = undefined;
                };

                const showSlide = (nextIndex) => {
                    activeIndex = (nextIndex + slides.length) % slides.length;
                    slides.forEach((slide, index) => {
                        const isActive = index === activeIndex;
                        slide.hidden = !isActive;
                        slide.setAttribute('aria-hidden', String(!isActive));
                    });
                    currentCount.textContent = String(activeIndex + 1).padStart(2, '0');
                };

                const startRotation = () => {
                    pauseRotation();
                    if (rotationPaused || reducedMotion || document.hidden || carousel.matches(':hover') || carousel.contains(document.activeElement)) return;

                    rotationTimer = window.setInterval(() => showSlide(activeIndex + 1), 5000);
                };

                carousel.querySelector('[data-course-previous]').addEventListener('click', () => {
                    showSlide(activeIndex - 1);
                    startRotation();
                });
                carousel.querySelector('[data-course-next]').addEventListener('click', () => {
                    showSlide(activeIndex + 1);
                    startRotation();
                });
                rotationToggle.addEventListener('click', () => {
                    rotationPaused = !rotationPaused;
                    rotationToggle.setAttribute('aria-pressed', String(rotationPaused));
                    rotationToggle.setAttribute('aria-label', rotationPaused ? 'Resume automatic slide rotation' : 'Pause automatic slide rotation');
                    rotationToggle.textContent = rotationPaused ? '▶' : 'Ⅱ';
                    startRotation();
                });

                carousel.addEventListener('mouseenter', pauseRotation);
                carousel.addEventListener('mouseleave', startRotation);
                carousel.addEventListener('focusin', pauseRotation);
                carousel.addEventListener('focusout', (event) => {
                    if (!carousel.contains(event.relatedTarget)) {
                        window.setTimeout(startRotation, 0);
                    }
                });
                document.addEventListener('visibilitychange', startRotation);
                startRotation();
            });

            function refreshCaptcha() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let captcha = '';
                for (let i = 0; i < 4; i++) {
                    if (i > 0) captcha += ' ';
                    captcha += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('sx-captcha-text').textContent = captcha;
            }
        </script>
    @endpush
@endsection
