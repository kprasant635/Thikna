@extends('layouts.dashboard')

@section('title', 'Dashboard — SkopX')

@section('content')

    @push('styles')
        <style>
            .referral-share-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 12px;
            }

            .referral-share-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                min-height: 38px;
                padding: 8px 12px;
                border: 0;
                border-radius: 7px;
                color: #fff;
                font: inherit;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
            }

            .referral-share-button svg {
                width: 17px;
                height: 17px;
                flex: none;
            }

            .referral-share-facebook {
                background: #1877f2;
            }

            .referral-share-whatsapp {
                background: #168b54;
            }

            .referral-share-instagram {
                background: #b83271;
            }

            .referral-share-button:focus-visible {
                outline: 3px solid #f59e0b;
                outline-offset: 2px;
            }
        </style>
    @endpush

    {{-- Flash Success / Activation Message --}}
    @if (session('success'))
        <div class="dash-welcome-banner" id="welcome-banner">
            <div class="dash-welcome-banner-content">
                <div class="dash-welcome-banner-icon">🎉</div>
                <div class="dash-welcome-banner-text">
                    <strong>Congratulations!</strong>
                    <p>{{ session('success') }}</p>
                    @if (Auth::user()->isActive())
                        <div style="margin-top: 8px;">
                            <a href="{{ route('subscription.receipt.download') }}" class="btn btn-sm"
                                style="background: #ffffff; color: #15803d; font-weight: 700; border: none; padding: 6px 14px; font-size: 13px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                📥 Download Payment Receipt (PDF)
                            </a>
                        </div>
                    @endif
                </div>
                <button type="button" class="dash-welcome-dismiss"
                    onclick="document.getElementById('welcome-banner').style.display='none'"
                    title="Dismiss">&times;</button>
            </div>
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="dash-welcome-section">
        <div>
            <h1 class="dash-welcome-heading">Welcome to SKOP-X, {{ Auth::user()->name }} 👋</h1>
            <p class="dash-welcome-sub">
                @if (Auth::user()->isActive())
                    You are an active SKOP-X member! Explore your courses and referral rewards below.
                @else
                    Your account is pending activation. Purchase at least 1 course to activate full benefits.
                @endif
            </p>
        </div>
    </div>

    {{-- Activation Required Banner if Pending --}}
    @if (!Auth::user()->isActive())
        <div class="dash-card"
            style="margin-bottom: 24px; background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%); border: 1.5px solid #ff9900; padding: 20px 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div
                        style="font-size: 12px; font-weight: 700; color: #c2410c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        ⚡ Account Activation Required
                    </div>
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        Purchase at least 1 course to activate your account &amp; unlock member benefits
                    </h3>
                    <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                        You can share your referral code/link anytime, but earnings &amp; rewards unlock after account
                        activation.
                    </p>
                </div>
                <a href="{{ route('subscription.show') }}" class="btn btn-gold"
                    style="padding: 11px 22px; font-size: 14px; white-space: nowrap;">
                    Purchase 1 Course Now →
                </a>
            </div>
        </div>
    @endif

    {{-- Learning & Certification Program Banner --}}
    <div class="dash-card"
        style="margin-bottom: 24px; background: linear-gradient(135deg, #f0f6ff 0%, #ffffff 100%); border: 1.5px solid #dbeafe; padding: 20px 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <div
                    style="font-size: 12px; font-weight: 700; color: #1a3a8f; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                    🎓 Learning &amp; Certification Program
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                    Complete educational courses to unlock your certificate &amp; rewards
                </h3>
                <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                    Certification Status: <strong
                        style="color: #1a3a8f;">{{ Auth::user()->isCertified() ? '✓ Certified Member' : 'In Progress' }}</strong>
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('courses.index') }}" class="btn btn-primary"
                    style="padding: 10px 18px; font-size: 13.5px; border-radius: var(--radius-sm);">
                    My Courses →
                </a>
                @if (Auth::user()->isCertified())
                    <a href="{{ route('certificate.show') }}" class="btn btn-outline"
                        style="padding: 10px 18px; font-size: 13.5px; border-radius: var(--radius-sm); border: 1.5px solid #1a3a8f; color: #1a3a8f;">
                        🏆 Certificate
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="dash-stats-row">
        <div class="dash-stat-card">
            <div class="dash-stat-icon"
                style="background: {{ Auth::user()->isActive() ? '#dcfce7' : '#fff7ed' }}; color: {{ Auth::user()->isActive() ? '#15803d' : '#c2410c' }};">
                <svg width="22" height="22" viewBox="0 0 20 20" fill="none">
                    <circle cx="10" cy="7" r="4" fill="currentColor" />
                    <path d="M3 18c0-3.866 3.134-7 7-7s7 3.134 7 7" fill="currentColor" opacity=".4" />
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Account Status</div>
                <div class="dash-stat-value"
                    style="color: {{ Auth::user()->isActive() ? '#15803d' : '#c2410c' }}; font-size: 15px;">
                    {{ Auth::user()->isActive() ? '✓ Active' : 'Pending (Needs 1 Course)' }}
                </div>
                @if (Auth::user()->isActive())
                    <a href="{{ route('subscription.receipt.download') }}"
                        style="display: inline-block; font-size: 12px; font-weight: 600; color: #15803d; text-decoration: underline; margin-top: 4px;">
                        📥 Download Receipt (PDF)
                    </a>
                @endif
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: var(--gold-tint); color: var(--gold);">
                <svg width="22" height="22" viewBox="0 0 20 20" fill="none">
                    <rect x="3" y="6" width="14" height="11" rx="2" fill="currentColor" opacity=".5" />
                    <path d="M7 6V4a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.5" fill="none" />
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">My Listings</div>
                <div class="dash-stat-value">0</div>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #EDE9FE; color: #7C3AED;">
                <svg width="22" height="22" viewBox="0 0 20 20" fill="none">
                    <path d="M10 3l2.09 4.26L17 8.27l-3.5 3.41.83 4.82L10 14.27l-4.33 2.23.83-4.82L3 8.27l4.91-1.01L10 3z"
                        fill="currentColor" />
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Your Referral Code</div>
                <div class="dash-stat-value dash-referral-code">{{ Auth::user()->referral_code ?? '—' }}</div>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #FEE2E2; color: #DC2626;">
                <svg width="22" height="22" viewBox="0 0 20 20" fill="none">
                    <path d="M10 18s-7-5.75-7-10.25C3 4.01 6.13 2 10 2s7 2.01 7 5.75S10 18 10 18z" fill="currentColor" />
                </svg>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Referrals</div>
                <div class="dash-stat-value">{{ Auth::user()->referrals()->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Dedicated Referral Sharing Card --}}
    <div class="dash-card"
        style="margin-bottom: 24px; padding: 22px 24px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 280px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span style="font-size: 20px;">🤝</span>
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 0;">Referral Program &amp; Invite
                        Link</h3>
                </div>
                <p style="font-size: 13.5px; color: #64748b; margin-bottom: 12px; line-height: 1.5;">
                    Share your code or link with friends. Anyone can register using your referral link anytime!
                </p>

                @php
                    $referralLink = route('register', ['ref' => Auth::user()->referral_code]);
                    $referralShareText = 'Join SKOP-X using my referral link: ' . $referralLink;
                @endphp

                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <div
                        style="background: #f1f5f9; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 14px; font-family: monospace; font-size: 15px; font-weight: 700; color: #1a3a8f;">
                        Code: {{ Auth::user()->referral_code }}
                    </div>
                    <div style="flex: 1; min-width: 220px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #334155; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                        id="refLinkText">
                        {{ $referralLink }}
                    </div>
                    <button type="button" class="btn btn-primary" onclick="copyReferralLink()"
                        style="padding: 9px 16px; font-size: 13px; border-radius: 8px; white-space: nowrap;">
                        📋 Copy Link
                    </button>
                </div>
                <div class="referral-share-actions" aria-label="Share referral link">
                    <a class="referral-share-button referral-share-facebook"
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}"
                        target="_blank" rel="noopener noreferrer" aria-label="Share referral link on Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.4 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.6V3.6c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H7.4v3.1h2.7v8h3.3z"/></svg>
                        Facebook
                    </a>
                    <a class="referral-share-button referral-share-whatsapp"
                        href="https://wa.me/?text={{ urlencode($referralShareText) }}"
                        target="_blank" rel="noopener noreferrer" aria-label="Share referral link on WhatsApp">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a9.8 9.8 0 0 0-8.4 14.8L2.3 22l5.4-1.4A10 10 0 1 0 12 2zm0 17.8a8 8 0 0 1-4.1-1.1l-.3-.2-3.2.8.9-3.1-.2-.3A8 8 0 1 1 12 19.8zm4.4-6c-.2-.1-1.5-.8-1.8-.9-.2-.1-.4-.1-.5.1l-.8 1c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.3.2-.4.7-1.3.1-.2 0-.4 0-.5l-.8-1.9c-.2-.5-.5-.4-.7-.4h-.5c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.3s1 2.6 1.1 2.8c.1.2 2 3 4.8 4.1 1.8.7 2.5.8 3.4.6.5-.1 1.5-.6 1.8-1.2.2-.6.2-1.1.2-1.2s-.2-.2-.4-.3z"/></svg>
                        WhatsApp
                    </a>
                    <button type="button" class="referral-share-button referral-share-instagram"
                        onclick="shareReferralOnInstagram()" aria-label="Share referral link on Instagram">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.8" r="1.2" fill="currentColor"/></svg>
                        Instagram
                    </button>
                </div>
                <div id="copyToast"
                    style="display: none; font-size: 12px; color: #16a34a; font-weight: 600; margin-top: 6px;">
                    ✓ Referral link copied to clipboard!
                </div>
            </div>
        </div>

        <div
            style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0; font-size: 12.5px; color: #64748b;">
            @if (Auth::user()->isActive())
                <span style="color: #16a34a; font-weight: 700;">✓ Account Active:</span> You will receive referral rewards
                &amp; benefits for all completed registrations.
            @else
                <span style="color: #c2410c; font-weight: 700;">⚡ Note:</span> Anyone can register using your code right
                now. Referral earnings &amp; benefits unlock when you purchase at least 1 course.
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            async function copyReferralLink(successMessage = 'Referral link copied to clipboard!') {
                const linkText = document.getElementById('refLinkText').innerText.trim();
                const toast = document.getElementById('copyToast');

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(linkText);
                    } else {
                        const temporaryInput = document.createElement('textarea');
                        temporaryInput.value = linkText;
                        temporaryInput.style.position = 'fixed';
                        temporaryInput.style.opacity = '0';
                        document.body.appendChild(temporaryInput);
                        temporaryInput.select();
                        const copied = document.execCommand('copy');
                        temporaryInput.remove();

                        if (!copied) {
                            throw new Error('Clipboard copy was not available.');
                        }
                    }

                    toast.textContent = successMessage;
                    toast.style.display = 'block';
                    window.setTimeout(() => {
                        toast.style.display = 'none';
                    }, 4000);
                } catch (error) {
                    window.prompt('Copy your referral link:', linkText);
                }
            }

            async function shareReferralOnInstagram() {
                const linkText = document.getElementById('refLinkText').innerText.trim();

                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Join SKOP-X',
                            text: 'Join SKOP-X using my referral link:',
                            url: linkText,
                        });
                        return;
                    } catch (error) {
                        if (error.name === 'AbortError') {
                            return;
                        }
                    }
                }

                await copyReferralLink('Referral link copied. Paste it into your Instagram post, story, or message.');
            }
        </script>
    @endpush

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
                    $filled = collect(['name', 'phone', 'address'])
                        ->filter(fn($f) => !empty($user->$f))
                        ->count();
                    $total = 4;
                    if (!empty($user->email)) {
                        $filled++;
                    }
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
                    <a href="{{ route('dashboard.profile') }}" class="btn btn-tint"
                        style="margin-top:14px; display:inline-block;">Complete Profile</a>
                @endif
            </div>
        </div>

        {{-- My Listings 
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>My Listings</h3>
            </div>
            <div class="dash-card-body">
                <div class="dash-empty-state">
                    <div class="dash-empty-icon">🏪</div>
                    <p>You haven't added any listings yet.</p>
                    <a href="{{ route('business.create') }}" class="btn btn-primary" style="margin-top:10px;">+ Add Your
                        First Listing</a>
                </div>
            </div>
        </div> --}}

        {{-- Quick Actions 
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
                    @if (Auth::user()->isActive())
                        <a href="{{ route('subscription.receipt.download') }}" class="dash-action-tile">
                            <span class="dash-action-icon">📄</span>
                            <span>Download Receipt</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
            --}}
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
