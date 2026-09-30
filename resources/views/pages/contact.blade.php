@extends('layouts.app')

@section('title', 'Contact Us — SKOP-X Support & Inquiry')

@section('content')
<div class="contact-page-wrapper">
    {{-- Hero Banner Section --}}
    <section class="contact-hero">
        <div class="wrap contact-hero-container">
            <div class="contact-hero-badge">
                <span class="badge-dot"></span>
                <span>We're Here To Help You 24/7</span>
            </div>
            <h1 class="contact-hero-title">Get in Touch with <span class="text-gradient">SKOP-X</span></h1>
            <p class="contact-hero-subtitle">
                Have questions about our courses, business listings, or income opportunities? Our team is dedicated to giving you fast, reliable support.
            </p>

            <div class="contact-hero-stats">
                <div class="hero-stat-pill">
                    <span class="stat-icon">⚡</span>
                    <div>
                        <strong>&lt; 2 Hours</strong>
                        <small>Avg. Response Time</small>
                    </div>
                </div>
                <div class="hero-stat-pill">
                    <span class="stat-icon">💬</span>
                    <div>
                        <strong>Instant Chat</strong>
                        <small>WhatsApp Helpline</small>
                    </div>
                </div>
                <div class="hero-stat-pill">
                    <span class="stat-icon">🌟</span>
                    <div>
                        <strong>99.4%</strong>
                        <small>Satisfaction Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wrap contact-main-content">
        {{-- Flash Alerts --}}
        @if (session('success'))
            <div class="contact-alert alert-success" id="contactSuccessAlert">
                <div class="alert-icon">🎉</div>
                <div class="alert-body">
                    <strong>Message Sent Successfully!</strong>
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="alert-close" onclick="document.getElementById('contactSuccessAlert').style.display='none'">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="contact-alert alert-danger" id="contactErrorAlert">
                <div class="alert-icon">⚠️</div>
                <div class="alert-body">
                    <strong>Please fix the errors below before submitting:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="alert-close" onclick="document.getElementById('contactErrorAlert').style.display='none'">✕</button>
            </div>
        @endif

        {{-- Main Contact Grid --}}
        <div class="contact-grid">
            {{-- Contact Information Column --}}
            <div class="contact-info-column">
                <div class="contact-section-header">
                    <span class="section-tag">Direct Channels</span>
                    <h2>How Would You Like to Connect?</h2>
                    <p>Reach out through any of our support channels below. We are eager to assist you!</p>
                </div>

                <div class="contact-cards-stack">
                    {{-- Card 1: Office Address --}}
                    <div class="info-card">
                        <div class="info-card-icon icon-blue">📍</div>
                        <div class="info-card-details">
                            <h3>Corporate Office</h3>
                            <p>{{ $contactInfo['address'] }}</p>
                            <span class="card-meta">🕒 {{ $contactInfo['hours'] }}</span>
                        </div>
                    </div>

                    {{-- Card 2: Phone Support --}}
                    <div class="info-card">
                        <div class="info-card-icon icon-green">📞</div>
                        <div class="info-card-details">
                            <h3>Call Us Directly</h3>
                            <p><a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}">{{ $contactInfo['phone'] }}</a></p>
                            <span class="card-meta">Toll-free customer helpline</span>
                        </div>
                    </div>

                    {{-- Card 3: Email Support --}}
                    <div class="info-card">
                        <div class="info-card-icon icon-purple">✉️</div>
                        <div class="info-card-details">
                            <h3>Official Email</h3>
                            <p><a href="mailto:{{ $contactInfo['email'] }}">{{ $contactInfo['email'] }}</a></p>
                            <span class="card-meta">For official queries & documentation</span>
                        </div>
                    </div>

                    {{-- Card 4: WhatsApp Support CTA --}}
                    <div class="info-card info-card-whatsapp">
                        <div class="info-card-icon icon-whatsapp">💬</div>
                        <div class="info-card-details">
                            <h3>WhatsApp Support Desk</h3>
                            <p>Chat with our support executive instantly on WhatsApp for immediate guidance.</p>
                            <a href="https://wa.me/{{ $contactInfo['whatsapp'] }}?text=Hello%20SKOP-X%20Support,%20I%20have%20a%20query" target="_blank" class="whatsapp-btn">
                                <span>Connect on WhatsApp</span>
                                <span class="arrow">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form Column --}}
            <div class="contact-form-column">
                <div class="contact-form-card">
                    <div class="form-card-header">
                        <h2>Send Us a Message</h2>
                        <p>Fill in the form below and our team will get back to you within 2 hours.</p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm" class="styled-contact-form">
                        @csrf

                        <div class="form-row gap-16">
                            {{-- Full Name --}}
                            <div class="form-group flex-1">
                                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <span class="input-icon">👤</span>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" placeholder="e.g. Rahul Sharma" required />
                                </div>
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Phone Number --}}
                            <div class="form-group flex-1">
                                <label for="phone" class="form-label">Phone / Mobile No. <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <span class="input-icon">📱</span>
                                    <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" placeholder="e.g. +91 98765 43210" required />
                                </div>
                                @error('phone')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row gap-16">
                            {{-- Email Address --}}
                            <div class="form-group flex-1">
                                <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <span class="input-icon">✉️</span>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" placeholder="e.g. rahul@example.com" required />
                                </div>
                                @error('email')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Inquiry Type --}}
                            <div class="form-group flex-1">
                                <label for="inquiry_type" class="form-label">Inquiry Category <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <span class="input-icon">🏷️</span>
                                    <select id="inquiry_type" name="inquiry_type" class="form-control @error('inquiry_type') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('inquiry_type') ? '' : 'selected' }}>Select Category</option>
                                        <option value="General Inquiry" {{ old('inquiry_type') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="Course & Learning" {{ old('inquiry_type') == 'Course & Learning' ? 'selected' : '' }}>Course & Learning</option>
                                        <option value="Business Listing & Franchise" {{ old('inquiry_type') == 'Business Listing & Franchise' ? 'selected' : '' }}>Business Listing & Franchise</option>
                                        <option value="Earning & Referral Plan" {{ old('inquiry_type') == 'Earning & Referral Plan' ? 'selected' : '' }}>Earning & Referral Plan</option>
                                        <option value="Technical Support" {{ old('inquiry_type') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                    </select>
                                </div>
                                @error('inquiry_type')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Subject --}}
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <span class="input-icon">📌</span>
                                <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Brief summary of your inquiry" required />
                            </div>
                            @error('subject')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div class="form-group">
                            <div class="label-row">
                                <label for="message" class="form-label">Your Message <span class="required">*</span></label>
                                <span class="char-counter" id="charCounter">0 / 2000</span>
                            </div>
                            <div class="input-with-icon textarea-icon-wrapper">
                                <span class="input-icon textarea-icon">💬</span>
                                <textarea id="message" name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Write down your detailed message or inquiry here..." maxlength="2000" required>{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="submit-btn" id="submitBtn">
                            <span class="btn-text">Submit Inquiry</span>
                            <span class="btn-icon">🚀</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- FAQ Accordion Section --}}
        <section class="contact-faq-section">
            <div class="faq-header">
                <span class="section-tag">Quick Answers</span>
                <h2>Frequently Asked Questions</h2>
                <p>Check these common questions before contacting us for instant solutions.</p>
            </div>

            <div class="faq-accordion-container">
                @foreach ($faqs as $index => $faq)
                    <div class="faq-item {{ $index === 0 ? 'active' : '' }}">
                        <button type="button" class="faq-question" onclick="toggleFaq(this)">
                            <span>{{ $faq['question'] }}</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Map / Office Location Section --}}
        <section class="contact-map-section">
            <div class="map-card">
                <div class="map-card-info">
                    <span class="section-tag">Visit Us</span>
                    <h2>SKOP-X Headquarters Location</h2>
                    <p>Located in the heart of Bhubaneswar, Odisha. Feel free to visit our campus during operational hours for direct consultation.</p>
                    <div class="map-details">
                        <div class="map-detail-item">
                            <strong>📍 Address:</strong>
                            <span>Plot No. 102/B, Saheed Nagar, Bhubaneswar, Odisha 751007</span>
                        </div>
                        <div class="map-detail-item">
                            <strong>⏰ Hours:</strong>
                            <span>Monday - Saturday: 9:00 AM - 7:00 PM</span>
                        </div>
                    </div>
                </div>
                <div class="map-embed-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3742.146603518296!2d85.83687311491745!3d20.29330998639908!2m3!1f0!0f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a1909e25d2d1413%3A0x6a297e6be9527f67!2sSaheed%20Nagar%2C%20Bhubaneswar%2C%20Odisha%20751007!5e0!3m2!1sen!2sin!4v1680000000000!5m2!1sen!2sin"
                        width="100%"
                        height="320"
                        style="border:0; border-radius: 12px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Character Counter for Message Textarea
    const messageInput = document.getElementById('message');
    const charCounter = document.getElementById('charCounter');

    if (messageInput && charCounter) {
        messageInput.addEventListener('input', function () {
            const currentLen = messageInput.value.length;
            charCounter.textContent = `${currentLen} / 2000`;
            if (currentLen > 1800) {
                charCounter.style.color = '#ef4444';
            } else {
                charCounter.style.color = 'var(--ink-soft)';
            }
        });
        // Initial trigger
        charCounter.textContent = `${messageInput.value.length} / 2000`;
    }

    // Toggle FAQ Accordion
    function toggleFaq(button) {
        const item = button.parentElement;
        const isActive = item.classList.contains('active');

        // Close all items
        document.querySelectorAll('.faq-item').forEach(el => {
            el.classList.remove('active');
            const icon = el.querySelector('.faq-icon');
            if (icon) icon.textContent = '+';
        });

        // If clicked item wasn't active, open it
        if (!isActive) {
            item.classList.add('active');
            const icon = item.querySelector('.faq-icon');
            if (icon) icon.textContent = '−';
        }
    }

    // Form submission feedback state
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    if (contactForm && submitBtn) {
        contactForm.addEventListener('submit', function () {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-text').textContent = 'Sending Message...';
            submitBtn.querySelector('.btn-icon').textContent = '⏳';
        });
    }
</script>
@endpush
