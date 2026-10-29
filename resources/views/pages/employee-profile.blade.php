@extends('layouts.app')

@section('title', 'Digital Employee Profile — {{ $employee->name }} — SKOP-X')

@section('content')
    <div class="employee-profile-page">
        <section class="profile-hero">
            <div class="wrap profile-hero-container">
                <h1 class="profile-title">Digital Employee Profile</h1>
                <p class="profile-subtitle">
                    Official SKOP-X digital identity. Scanned from the Virtual Member ID Card.
                </p>
            </div>
        </section>

        <div class="wrap profile-body">
            <article class="profile-card">
                <div class="profile-header">
                    <div class="profile-photo">
                        @if ($employee->profilePhotoUrl())
                            <img src="{{ $employee->profilePhotoUrl() }}"
                                 alt="{{ $employee->name }}"
                                 class="profile-photo-img">
                        @else
                            <div class="profile-photo-initials">
                                {{ $employee->initials() }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-identity">
                        <span class="profile-badge">ID:
                            {{ $employee->referral_code ?? ('THK' . str_pad($employee->id, 5, '0', STR_PAD_LEFT)) }}
                        </span>
                        <h2 class="profile-name">{{ $employee->name }}</h2>
                        <p class="profile-designation">{{ $employee->designation() }}</p>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <span class="detail-label">Email ID</span>
                        <span class="detail-value">
                            {{ $employee->email ?? 'Not provided' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Mobile Number</span>
                        <span class="detail-value">
                            +91 {{ $employee->phone ?? 'Not provided' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Designation / Position</span>
                        <span class="detail-value">{{ $employee->designation() }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Joining Date</span>
                        <span class="detail-value">
                            {{ $employee->created_at ? $employee->created_at->format('d M Y') : 'Not available' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Current Level / Achievement</span>
                        <span class="detail-value achievement-badge">
                            {{ $employee->achievementLevel() }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Account Status</span>
                        <span class="detail-value status-badge {{ $employee->isActive() ? 'status-active' : 'status-pending' }}">
                            @if ($employee->isActive())
                                ✓ Active Member
                            @else
                                ⚡ Pending
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Office Details Displayed on ID Card QR Scan -->
                <section class="profile-office">
                    <h3 class="office-heading">🏢 SKOP-X Corporate Office</h3>
                    <div class="office-grid">
                        <div class="office-item">
                            <span class="office-label">Office Address</span>
                            <span class="office-value">
                                SKOP-X Towers, Plot No. 102/B, Saheed Nagar,
                                Bhubaneswar, Odisha - 751007
                            </span>
                        </div>
                        <div class="office-item">
                            <span class="office-label">Office Hours</span>
                            <span class="office-value">Mon - Sat: 9:00 AM - 7:00 PM</span>
                        </div>
                        <div class="office-item">
                            <span class="office-label">Phone</span>
                            <span class="office-value">+91 98765 43210</span>
                        </div>
                        <div class="office-item">
                            <span class="office-label">Email</span>
                            <span class="office-value">support@skop-x.in</span>
                        </div>
                    </div>
                </section>

                <div class="profile-footer">
                    <p class="profile-note">
                        This profile is generated live from SKOP-X records. Updates to designation, level, or
                        achievement are reflected here automatically — no new ID card needs to be printed.
                    </p>
                    <p class="profile-note">
                        Need help? Contact <a href="mailto:support@skop-x.in">support@skop-x.in</a>.
                    </p>
                </div>
            </article>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile-office {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
        }

        .office-heading {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .office-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .office-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12px;
        }

        .office-label {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            display: block;
            margin-bottom: 4px;
        }

        .office-value {
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }
    </style>
@endpush
