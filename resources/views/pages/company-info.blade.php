@extends('layouts.app')

@section('title', 'Company Information Hub — SKOP-X')

@section('content')
    <div class="company-info-page">
        <section class="info-hero">
            <div class="wrap info-hero-container">
                <div class="info-badge">Official SKOP-X Information Hub</div>
                <h1 class="info-title">Company Information</h1>
                <p class="info-subtitle">
                    Scan to explore SKOP-X — office details, objectives, learning content, fitness, cooking,
                    economic awareness, tourism, and media resources.
                </p>
            </div>
        </section>

        <div class="wrap info-body">
            <!-- Company Address & Office Photographs -->
            <section class="info-section" id="office">
                <h2 class="section-title">🏢 Company Address &amp; Office</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="card-label">Office Address</div>
                        <div class="card-value">
                            SKOP-X Towers, Plot No. 102/B, Saheed Nagar,<br>
                            Bhubaneswar, Khordha District, Odisha - 751007
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="card-label">Office Hours</div>
                        <div class="card-value">Monday - Saturday: 9:00 AM - 7:00 PM</div>
                    </div>
                    <div class="info-card">
                        <div class="card-label">Contact</div>
                        <div class="card-value">
                            📞 +91 98765 43210<br>
                            ✉️ support@skop-x.in
                        </div>
                    </div>
                    <div class="info-card office-photos">
                        <div class="card-label">Office Photographs</div>
                        <div class="photo-strip">
                            <img src="{{ asset('images/home-rental-beltola.jpg') }}" alt="Office photo 1" class="office-photo">
                            <img src="{{ asset('images/home-rental-chandmari.jpg') }}" alt="Office photo 2" class="office-photo">
                            <img src="{{ asset('images/home-rental-hengrabari.jpg') }}" alt="Office photo 3" class="office-photo">
                            <img src="{{ asset('images/growth-plant.jpg') }}" alt="Office photo 4" class="office-photo">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Company Objectives & Work Details -->
            <section class="info-section" id="objectives">
                <h2 class="section-title">🎯 Company Objectives &amp; Work Details</h2>
                <div class="objectives-grid">
                    <div class="objective-item">
                        <span class="obj-number">01</span>
                        <div class="obj-content">
                            <h3>Skill Development</h3>
                            <p>Empowering individuals with professional training and certification courses for career growth.</p>
                        </div>
                    </div>
                    <div class="objective-item">
                        <span class="obj-number">02</span>
                        <div class="obj-content">
                            <h3>Income Opportunities</h3>
                            <p>Creating earning and referral opportunities for members, educators, and associates.</p>
                        </div>
                    </div>
                    <div class="objective-item">
                        <span class="obj-number">03</span>
                        <div class="obj-content">
                            <h3>Business Listings</h3>
                            <p>Helping local businesses list, promote, and grow through the SKOP-X yellow pages platform.</p>
                        </div>
                    </div>
                    <div class="objective-item">
                        <span class="obj-number">04</span>
                        <div class="obj-content">
                            <h3>Community Growth</h3>
                            <p>Building a skilled, certified, and self-reliant community aligned with Skill India and Atmanirbhar Bharat.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tourism & Destination Information -->
            <section class="info-section" id="tourism">
                <h2 class="section-title">🗺️ Tourism &amp; Destination Information</h2>
                <p class="section-intro">
                    Explore popular travel destinations, local attractions, and tourism resources across Odisha and India.
                </p>
                <div class="tourism-grid">
                    <div class="tourism-card">
                        <img src="{{ asset('images/rental-apartment-2bhk.jpg') }}" alt="Destination" class="tourism-img">
                        <div class="tourism-info">
                            <h3>Bhubaneswar</h3>
                            <p>Temple City of India — rich heritage and cultural landmarks.</p>
                        </div>
                    </div>
                    <div class="tourism-card">
                        <img src="{{ asset('images/rental-penthouse-3bhk.jpg') }}" alt="Destination" class="tourism-img">
                        <div class="tourism-info">
                            <h3>Puri</h3>
                            <p>Famous for Jagannath Temple and serene sea beaches.</p>
                        </div>
                    </div>
                    <div class="tourism-card">
                        <img src="{{ asset('images/rental-studio-1bhk.jpg') }}" alt="Destination" class="tourism-img">
                        <div class="tourism-info">
                            <h3>Cuttack</h3>
                            <p>Historic city known for silver filigree work and Barabati Fort.</p>
                        </div>
                    </div>
                    <div class="tourism-card">
                        <img src="{{ asset('images/shop-mobile-hub.jpg') }}" alt="Destination" class="tourism-img">
                        <div class="tourism-info">
                            <h3>Konark</h3>
                            <p>UNESCO World Heritage Sun Temple and Chandrabhaga beach.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Educational Information & Learning Content -->
            <section class="info-section" id="education">
                <h2 class="section-title">📚 Educational Information &amp; Learning Content</h2>
                <p class="section-intro">
                    Skill development courses, certifications, and learning programs offered by SKOP-X.
                </p>
                <div class="course-grid">
                    @forelse ($courses as $course)
                        <div class="course-card">
                            <img src="{{ $course->img_url ?? asset('images/courses/education.png') }}"
                                 alt="{{ $course->title }}" class="course-img">
                            <div class="course-info">
                                <h3>{{ $course->title }}</h3>
                                <p>{{ Str::limit($course->description ?? 'Professional skill development course.', 120) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="course-card">
                            <div class="course-info">
                                <h3>Skill Development Programs</h3>
                                <p>Explore our range of professional training and certification courses.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Zumba, Yoga & Fitness Content -->
            <section class="info-section" id="fitness">
                <h2 class="section-title">🧘 Zumba, Yoga &amp; Fitness Content</h2>
                <p class="section-intro">
                    Wellness programs including Zumba, yoga, exercise, and fitness training for a healthier lifestyle.
                </p>
                <div class="fitness-grid">
                    <div class="fitness-card">
                        <img src="{{ asset('images/courses/yoga_exercise.png') }}" alt="Yoga" class="fitness-img">
                        <div class="fitness-info">
                            <h3>Yoga &amp; Exercise</h3>
                            <p>Daily yoga sessions and exercise routines for physical and mental well-being.</p>
                        </div>
                    </div>
                    <div class="fitness-card">
                        <img src="{{ asset('images/courses/zumba_exercise.png') }}" alt="Zumba" class="fitness-img">
                        <div class="fitness-info">
                            <h3>Zumba Fitness</h3>
                            <p>Energetic dance-based workout programs for cardio and fun fitness.</p>
                        </div>
                    </div>
                    <div class="fitness-card">
                        <img src="{{ asset('images/courses/health.png') }}" alt="Health" class="fitness-img">
                        <div class="fitness-info">
                            <h3>Health &amp; Wellness</h3>
                            <p>Nutrition, health tips, and wellness guidance for active living.</p>
                        </div>
                    </div>
                    <div class="fitness-card">
                        <img src="{{ asset('images/courses/sports_local.png') }}" alt="Sports" class="fitness-img">
                        <div class="fitness-info">
                            <h3>Sports &amp; Local Activities</h3>
                            <p>Community sports events and local fitness activities.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cooking-Related Content -->
            <section class="info-section" id="cooking">
                <h2 class="section-title">🍳 Cooking-Related Content</h2>
                <p class="section-intro">
                    Culinary training, recipes, home delivery concepts, and cooking education programs.
                </p>
                <div class="cooking-grid">
                    <div class="cooking-card">
                        <img src="{{ asset('images/courses/cook_home_delivery.png') }}" alt="Cooking" class="cooking-img">
                        <div class="cooking-info">
                            <h3>Cook &amp; Home Delivery</h3>
                            <p>Home cooking skills and food delivery service training.</p>
                        </div>
                    </div>
                    <div class="cooking-card">
                        <img src="{{ asset('images/courses/economic.png') }}" alt="Economic" class="cooking-img">
                        <div class="cooking-info">
                            <h3>Economic Cooking</h3>
                            <p>Budget-friendly meal planning and economical cooking techniques.</p>
                        </div>
                    </div>
                    <div class="cooking-card">
                        <img src="{{ asset('images/courses/fashion_training.png') }}" alt="Training" class="cooking-img">
                        <div class="cooking-info">
                            <h3>Food &amp; Hospitality</h3>
                            <p>Food service, hospitality, and catering training programs.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Economic Awareness -->
            <section class="info-section" id="economic">
                <h2 class="section-title">💰 Economic Awareness &amp; Related Information</h2>
                <p class="section-intro">
                    Financial literacy, earning plans, subscription models, and economic empowerment resources.
                </p>
                <div class="economic-grid">
                    <div class="economic-card">
                        <h3>Earning &amp; Referral Plan</h3>
                        <p>Learn how members can earn through referrals and platform participation.</p>
                    </div>
                    <div class="economic-card">
                        <h3>Subscription Plans</h3>
                        <p>Affordable course and service subscriptions for skill development.</p>
                    </div>
                    <div class="economic-card">
                        <h3>Business Listings</h3>
                        <p>List your business and reach more customers through SKOP-X yellow pages.</p>
                    </div>
                    <div class="economic-card">
                        <h3>Financial Freedom</h3>
                        <p>Guidance on achieving financial independence through skill-based income.</p>
                    </div>
                </div>
            </section>

            <!-- Videos, Photographs & Media -->
            <section class="info-section" id="media">
                <h2 class="section-title">🎥 Videos, Photographs &amp; Relevant Content</h2>
                <p class="section-intro">
                    Promotional videos, learner photos, success stories, and other media content from SKOP-X.
                </p>
                <div class="media-grid">
                    <div class="media-card">
                        <img src="{{ asset('images/hero-female-learner.jpg') }}" alt="Learner" class="media-img">
                        <div class="media-info">
                            <h3>Learner Stories</h3>
                            <p>Real stories from students and professionals who grew with SKOP-X.</p>
                        </div>
                    </div>
                    <div class="media-card">
                        <img src="{{ asset('images/promo-male-learner.jpg') }}" alt="Promo" class="media-img">
                        <div class="media-info">
                            <h3>Promotional Videos</h3>
                            <p>Course introductions, earning plan explainers, and platform tours.</p>
                        </div>
                    </div>
                    <div class="media-card">
                        <img src="{{ asset('images/growth-plant.jpg') }}" alt="Growth" class="media-img">
                        <div class="media-info">
                            <h3>Success Gallery</h3>
                            <p>Achiever photos, certification moments, and community events.</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="info-footer-note">
                <p>
                    SKOP-X — Educational, Training &amp; Certification Platform.<br>
                    Skill India | Digital India | Atmanirbhar Bharat | Better Tomorrow
                </p>
                <p>
                    Need help? Contact <a href="mailto:support@skop-x.in">support@skop-x.in</a> or
                    <a href="tel:+919876543210">+91 98765 43210</a>.
                </p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-hero {
            background: linear-gradient(135deg, #0a1628 0%, #12263f 100%);
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }
        .info-badge {
            display: inline-block;
            background: rgba(255,255,255,0.12);
            color: #fff;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .info-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 10px;
            letter-spacing: -0.5px;
        }
        .info-subtitle {
            font-size: 15px;
            color: rgba(255,255,255,0.75);
            margin: 0;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .info-body {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 16px;
        }
        .info-section {
            margin-bottom: 48px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: #0a1628;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-intro {
            font-size: 14px;
            color: #475569;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px;
            font-size: 13.5px;
            color: #334155;
            line-height: 1.6;
        }
        .card-label {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
            display: block;
        }
        .card-value {
            font-weight: 600;
            color: #0f172a;
        }
        .office-photos .card-label {
            margin-bottom: 10px;
        }
        .photo-strip {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .office-photo {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        .objectives-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }
        .objective-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0f5e2e;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            gap: 12px;
        }
        .obj-number {
            width: 32px;
            height: 32px;
            background: #0f5e2e;
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .obj-content h3 {
            font-size: 15px;
            margin: 0 0 6px;
            color: #0a1628;
        }
        .obj-content p {
            font-size: 13px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }
        .tourism-grid, .course-grid, .fitness-grid, .cooking-grid, .economic-grid, .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .tourism-card, .course-card, .fitness-card, .cooking-card, .media-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .tourism-img, .course-img, .fitness-img, .cooking-img, .media-img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #e2e8f0;
        }
        .tourism-info, .course-info, .fitness-info, .cooking-info, .media-info {
            padding: 14px;
        }
        .tourism-info h3, .course-info h3, .fitness-info h3, .cooking-info h3, .media-info h3 {
            font-size: 15px;
            margin: 0 0 6px;
            color: #0a1628;
        }
        .tourism-info p, .course-info p, .fitness-info p, .cooking-info p, .media-info p {
            font-size: 12.5px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }
        .economic-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
        }
        .economic-card h3 {
            font-size: 15px;
            margin: 0 0 8px;
            color: #0a1628;
        }
        .economic-card p {
            font-size: 13px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }
        .info-footer-note {
            margin-top: 32px;
            padding: 20px;
            background: #0a1628;
            color: rgba(255,255,255,0.85);
            border-radius: 12px;
            text-align: center;
            font-size: 13.5px;
            line-height: 1.7;
        }
        .info-footer-note a {
            color: #38bdf8;
            text-decoration: underline;
        }
    </style>
@endpush