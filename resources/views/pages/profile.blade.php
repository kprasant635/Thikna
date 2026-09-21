@extends('layouts.dashboard')

@section('title', 'My Profile & Virtual ID Card — SkopX')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="dash-welcome-banner" id="flash-banner">
            <div class="dash-welcome-banner-content">
                <div class="dash-welcome-banner-icon">✅</div>
                <div class="dash-welcome-banner-text">
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="dash-welcome-dismiss"
                    onclick="document.getElementById('flash-banner').style.display='none'">&times;</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="dash-welcome-banner" style="border-color: #FEB2B2; background: #FFF5F5;" id="error-banner">
            <div class="dash-welcome-banner-content">
                <div class="dash-welcome-banner-icon">⚠️</div>
                <div class="dash-welcome-banner-text">
                    <ul style="margin: 0; padding-left: 18px; color: #C53030;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="dash-welcome-dismiss"
                    onclick="document.getElementById('error-banner').style.display='none'">&times;</button>
            </div>
        </div>
    @endif

    <div class="dash-welcome-section">
        <div>
            <h1 class="dash-welcome-heading">My Profile &amp; Digital ID Card</h1>
            <p class="dash-welcome-sub">Update your profile photo, personal information, and view or download your Virtual
                ID Card.</p>
        </div>
    </div>

    <div class="dash-profile-layout">

        <!-- Left Column: Personal Information Form -->
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Personal Information</h3>
            </div>
            <div class="dash-card-body">
                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data"
                    class="register-form">
                    @csrf
                    @method('PUT')

                    <!-- Profile Photo Upload Field -->
                    <div class="field photo-upload-field">
                        <label>Profile Photo</label>
                        <div class="photo-upload-container">
                            <div class="avatar-preview-box">
                                @if (Auth::user()->profilePhotoUrl())
                                    <img id="avatarPreview" src="{{ Auth::user()->profilePhotoUrl() }}" alt="Profile Photo">
                                @else
                                    <div id="initialsAvatar" class="avatar-initials">{{ Auth::user()->initials() }}</div>
                                    <img id="avatarPreview" src="" alt="Profile Photo" style="display: none;">
                                @endif
                            </div>
                            <div class="photo-input-group">
                                <label for="profile_photo" class="btn btn-outline photo-select-btn">
                                    📷 Upload New Photo
                                </label>
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                    onchange="previewSelectedPhoto(this)" style="display: none;">
                                <div class="photo-hint">JPG, PNG, WEBP max 5MB. Square photo recommended.</div>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Full name <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    <div class="field">
                        <label>Mobile number</label>
                        <input type="tel" value="+91 {{ Auth::user()->phone }}" readonly
                            style="background: var(--green-tint); cursor: not-allowed;">
                        <div style="font-size: 12px; color: var(--ink-soft); margin-top: 2px;">Mobile number cannot be
                            changed.</div>
                    </div>

                    <div class="field">
                        <label>Email address <span class="optional">(optional)</span></label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                            placeholder="priya@example.com">
                    </div>

                    <div class="field">
                        <label>Complete address <span class="req">*</span></label>
                        <textarea name="address" rows="3" required>{{ old('address', Auth::user()->address) }}</textarea>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                            💾 Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Virtual Member ID Card Preview & Actions -->
        <div class="id-card-section">
            <div class="dash-card">
                <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>🪪 Virtual Member ID Card</h3>
                    <span class="id-live-tag">Live Preview</span>
                </div>
                <div class="dash-card-body" style="text-align: center;">

                    <!-- Graphical Virtual ID Card -->
                    <div class="virtual-id-card" id="virtualIdCard">
                        <div class="vcard-header">
                            <div class="vcard-brand">SKOP-X</div>
                            <div class="vcard-title">OFFICIAL DIGITAL MEMBER PASS</div>
                        </div>

                        <div class="vcard-photo-wrapper">
                            @if (Auth::user()->profilePhotoUrl())
                                <img id="idCardPhoto" src="{{ Auth::user()->profilePhotoUrl() }}" alt="Member Photo"
                                    class="vcard-photo">
                            @else
                                <div id="idCardInitials" class="vcard-initials">{{ Auth::user()->initials() }}</div>
                                <img id="idCardPhoto" src="" alt="Member Photo" class="vcard-photo"
                                    style="display: none;">
                            @endif
                        </div>

                        <div class="vcard-body">
                            <div class="vcard-name">{{ Auth::user()->name }}</div>
                            <div class="vcard-id-badge">
                                ID:
                                {{ Auth::user()->referral_code ?? 'THK' . str_pad(Auth::user()->id, 5, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="vcard-grid">
                                <div class="vcard-item">
                                    <span class="vlabel">Mobile:</span>
                                    <span class="vval">+91 {{ Auth::user()->phone }}</span>
                                </div>
                                @if (Auth::user()->email)
                                    <div class="vcard-item">
                                        <span class="vlabel">Email:</span>
                                        <span class="vval">{{ Auth::user()->email }}</span>
                                    </div>
                                @endif
                                <div class="vcard-item">
                                    <span class="vlabel">Account:</span>
                                    <span class="vval">
                                        @if (Auth::user()->isActive())
                                            <span class="vstatus-badge active">✓ Active Member</span>
                                        @else
                                            <span class="vstatus-badge pending">⚡ Pending</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="vcard-item">
                                    <span class="vlabel">Certified:</span>
                                    <span class="vval">
                                        @if (Auth::user()->isCertified())
                                            <strong style="color: #15803d;">🏆 Certified</strong>
                                        @else
                                            <span style="color: #64748b;">In Progress</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="vcard-item">
                                    <span class="vlabel">Member Since:</span>
                                    <span class="vval">{{ Auth::user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="vcard-footer">
                            <div class="vcard-auth">VERIFIED DIGITAL IDENTITY</div>
                            <div class="vcard-barcode">||| |||| | ||| || |||| |||</div>
                        </div>
                    </div>

                    <!-- ID Card Download Buttons -->
                    <div class="id-card-actions">
                        <a href="{{ route('dashboard.idcard.download') }}" class="btn btn-gold"
                            style="width: 100%; justify-content: center; padding: 12px; font-weight: 700;">
                            📥 Download Virtual ID Card (PDF)
                        </a>
                        <button type="button" onclick="window.print()" class="btn btn-outline"
                            style="width: 100%; justify-content: center; padding: 10px; margin-top: 8px;">
                            🖨️ Print ID Pass
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- My Referral List Section -->
        <div class="dash-card" style="margin-top: 28px;">
            <div class="dash-card-header"
                style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h3 style="margin: 0; font-size: 18px;">👥 My Referral List</h3>
                    <div style="font-size: 12.5px; color: var(--ink-soft); font-weight: normal; margin-top: 2px;">Members
                        who joined using your referral code <strong>{{ Auth::user()->referral_code }}</strong></div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div class="ref-search-box">
                        <span class="ref-search-icon">🔍</span>
                        <input type="text" id="profileReferralSearchInput" onkeyup="filterProfileReferralsTable()"
                            placeholder="Search referrals…">
                    </div>
                    <div class="referral-count-badge">
                        Total:
                        <strong>{{ isset($referrals) ? $referrals->count() : Auth::user()->referrals()->count() }}</strong>
                    </div>
                    <a href="{{ route('dashboard.referrals') }}" class="btn btn-outline"
                        style="padding: 7px 14px; font-size: 12.5px;">
                        View Full Screen →
                    </a>
                </div>
            </div>

            <div class="dash-card-body" style="padding: 0;">
                @php
                    $refList = isset($referrals)
                        ? $referrals
                        : Auth::user()->referrals()->withCount('referrals')->latest()->get();
                @endphp

                @if ($refList->count() > 0)
                    <div class="table-responsive">
                        <table class="referrals-table" id="profileReferralsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Mobile Number</th>
                                    <th>Joined Date</th>
                                    <th>Subscription Status</th>
                                    <th>Learning Status</th>
                                    <th>Referrals Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($refList as $index => $ref)
                                    @php
                                        $subCount = $ref->referrals_count ?? $ref->referrals()->count();
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="ref-user-cell">
                                                <div class="ref-user-avatar">
                                                    @if ($ref->profilePhotoUrl())
                                                        <img src="{{ $ref->profilePhotoUrl() }}"
                                                            alt="{{ $ref->name }}">
                                                    @else
                                                        <span>{{ $ref->initials() }}</span>
                                                    @endif
                                                </div>
                                                <strong>{{ $ref->name }}</strong>
                                            </div>
                                        </td>
                                        <td>+91 {{ substr($ref->phone, 0, 3) . '*****' . substr($ref->phone, -2) }}</td>
                                        <td>{{ $ref->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if ($ref->isActive())
                                                <span class="ref-badge status-active">✓ Active (Subscribed)</span>
                                            @else
                                                <span class="ref-badge status-pending">⚡ Pending (Unsubscribed)</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ref->isCertified())
                                                <span class="ref-badge status-certified">🏆 Certified</span>
                                            @else
                                                <span class="ref-badge status-in-progress">In Progress</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="ref-count-pill">
                                                👥 <strong>{{ $subCount }}</strong> referral(s)
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="profileNoResultsMsg"
                        style="display: none; padding: 24px; text-align: center; color: #64748b;">
                        🔍 No referrals match your search.
                    </div>
                @else
                    <div class="dash-empty-state" style="padding: 36px 20px; text-align: center;">
                        <div class="dash-empty-icon" style="font-size: 36px; margin-bottom: 10px;">🤝</div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">No Referrals Yet
                        </h4>
                        <p style="font-size: 13.5px; color: #64748b; margin-bottom: 16px;">Share your referral code
                            <strong>{{ Auth::user()->referral_code }}</strong> to invite friends &amp; unlock rewards!
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary"
                            style="padding: 9px 18px; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                            📋 Get Referral Link
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                function previewSelectedPhoto(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatarImg = document.getElementById('avatarPreview');
                            const initialsDiv = document.getElementById('initialsAvatar');

                            const cardImg = document.getElementById('idCardPhoto');
                            const cardInitials = document.getElementById('idCardInitials');

                            if (avatarImg) {
                                avatarImg.src = e.target.result;
                                avatarImg.style.display = 'block';
                            }
                            if (initialsDiv) {
                                initialsDiv.style.display = 'none';
                            }

                            if (cardImg) {
                                cardImg.src = e.target.result;
                                cardImg.style.display = 'block';
                            }
                            if (cardInitials) {
                                cardInitials.style.display = 'none';
                            }
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function filterProfileReferralsTable() {
                    const searchInput = document.getElementById('profileReferralSearchInput').value.toLowerCase().trim();
                    const table = document.getElementById('profileReferralsTable');
                    if (!table) return;

                    const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                    let visibleCount = 0;

                    for (let i = 0; i < trs.length; i++) {
                        const tr = trs[i];
                        const rowText = tr.innerText.toLowerCase();
                        if (searchInput === '' || rowText.includes(searchInput)) {
                            tr.style.display = '';
                            visibleCount++;
                        } else {
                            tr.style.display = 'none';
                        }
                    }

                    const noResults = document.getElementById('profileNoResultsMsg');
                    if (noResults) {
                        noResults.style.display = (visibleCount === 0 && trs.length > 0) ? 'block' : 'none';
                    }
                }
            </script>
        @endpush

        @push('styles')
            <style>
                /* Referral List Component */
                .referral-count-badge {
                    background: #e6fffa;
                    color: #234e52;
                    border: 1px solid #b2f5ea;
                    font-size: 13px;
                    padding: 6px 14px;
                    border-radius: 999px;
                }

                .ref-search-box {
                    position: relative;
                    display: flex;
                    align-items: center;
                }

                .ref-search-icon {
                    position: absolute;
                    left: 10px;
                    font-size: 12px;
                    color: #94a3b8;
                    pointer-events: none;
                }

                .ref-search-box input {
                    padding: 6px 12px 6px 30px;
                    border: 1.5px solid var(--line);
                    border-radius: var(--radius-sm);
                    font-size: 12.5px;
                    width: 200px;
                    background: #ffffff;
                }

                .ref-search-box input:focus {
                    outline: none;
                    border-color: var(--green-deep);
                    box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
                }

                .referrals-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 13.5px;
                    text-align: left;
                }

                .referrals-table th {
                    background: #f8fafc;
                    padding: 12px 16px;
                    font-weight: 700;
                    color: var(--ink);
                    border-bottom: 1.5px solid var(--line);
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .referrals-table td {
                    padding: 14px 16px;
                    border-bottom: 1px solid var(--line);
                    vertical-align: middle;
                }

                .referrals-table tr:hover {
                    background: #fcfdfd;
                }

                .ref-user-cell {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .ref-user-avatar {
                    width: 34px;
                    height: 34px;
                    border-radius: 50%;
                    background: #0f5e2e;
                    color: white;
                    font-weight: bold;
                    font-size: 13px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    flex-shrink: 0;
                }

                .ref-user-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .ref-badge {
                    display: inline-block;
                    font-size: 11px;
                    font-weight: 700;
                    padding: 3px 10px;
                    border-radius: 999px;
                }

                .ref-badge.status-active {
                    background: #dcfce7;
                    color: #15803d;
                }

                .ref-badge.status-pending {
                    background: #fff7ed;
                    color: #c2410c;
                }

                .ref-badge.status-certified {
                    background: #e0e7ff;
                    color: #3730a3;
                }

                .ref-badge.status-in-progress {
                    background: #f1f5f9;
                    color: #64748b;
                }

                .ref-count-pill {
                    display: inline-block;
                    background: #f1f5f9;
                    border: 1px solid #cbd5e1;
                    padding: 3px 8px;
                    border-radius: 6px;
                    font-size: 12px;
                    color: #334155;
                }

                .table-responsive {
                    overflow-x: auto;
                }

                .dash-profile-layout {
                    display: grid;
                    grid-template-columns: 1fr 340px;
                    gap: 24px;
                    align-items: start;
                }

                @media (max-width: 900px) {
                    .dash-profile-layout {
                        grid-template-columns: 1fr;
                    }
                }

                .register-form .field {
                    margin-bottom: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                .register-form label {
                    font-size: 13.5px;
                    font-weight: 600;
                    color: var(--ink);
                }

                .register-form label .req {
                    color: #e53e3e;
                }

                .register-form label .optional {
                    color: var(--ink-soft);
                    font-weight: 400;
                    font-size: 12px;
                }

                .register-form input[type="text"],
                .register-form input[type="email"],
                .register-form input[type="tel"],
                .register-form textarea {
                    width: 100%;
                    padding: 11px 14px;
                    border: 1.5px solid var(--line);
                    border-radius: var(--radius-sm);
                    font-size: 14px;
                    font-family: 'Inter', sans-serif;
                    color: var(--ink);
                    background: #fff;
                    transition: border-color .15s ease, box-shadow .15s ease;
                }

                .register-form input:focus,
                .register-form textarea:focus {
                    outline: none;
                    border-color: var(--green-deep);
                    box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
                }

                /* Photo Upload Styles */
                .photo-upload-container {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    background: #f8fafc;
                    border: 1.5px dashed #cbd5e1;
                    border-radius: var(--radius-md);
                    padding: 16px;
                }

                .avatar-preview-box {
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    overflow: hidden;
                    background: #0f5e2e;
                    flex-shrink: 0;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                }

                .avatar-preview-box img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .avatar-initials {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 24px;
                    font-weight: 700;
                }

                .photo-input-group {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                }

                .photo-select-btn {
                    padding: 8px 14px;
                    font-size: 13px;
                    cursor: pointer;
                    border-radius: var(--radius-sm);
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .photo-hint {
                    font-size: 11.5px;
                    color: var(--ink-soft);
                }

                /* Virtual ID Card Design */
                .id-live-tag {
                    font-size: 11px;
                    font-weight: 700;
                    background: #dcfce7;
                    color: #15803d;
                    padding: 3px 8px;
                    border-radius: 999px;
                }

                .virtual-id-card {
                    width: 100%;
                    max-width: 300px;
                    margin: 0 auto 20px;
                    background: #ffffff;
                    border-radius: 16px;
                    border: 2px solid #0f5e2e;
                    box-shadow: 0 12px 28px rgba(15, 94, 46, 0.15);
                    overflow: hidden;
                    position: relative;
                    transition: transform 0.25s ease;
                }

                .virtual-id-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 16px 32px rgba(15, 94, 46, 0.22);
                }

                .vcard-header {
                    background: linear-gradient(135deg, #0f5e2e 0%, #06371a 100%);
                    color: white;
                    padding: 16px 12px 32px;
                }

                .vcard-brand {
                    font-size: 20px;
                    font-weight: 800;
                    letter-spacing: 1px;
                }

                .vcard-title {
                    font-size: 8.5px;
                    opacity: 0.9;
                    letter-spacing: 0.8px;
                    text-transform: uppercase;
                }

                .vcard-photo-wrapper {
                    margin-top: -26px;
                    margin-bottom: 10px;
                }

                .vcard-photo {
                    width: 76px;
                    height: 76px;
                    border-radius: 50%;
                    border: 3.5px solid #ffffff;
                    object-fit: cover;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
                    margin: 0 auto;
                    background: #e2e8f0;
                }

                .vcard-initials {
                    width: 76px;
                    height: 76px;
                    border-radius: 50%;
                    border: 3.5px solid #ffffff;
                    background: #0f5e2e;
                    color: white;
                    font-size: 26px;
                    font-weight: 700;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
                }

                .vcard-body {
                    padding: 0 14px 14px;
                }

                .vcard-name {
                    font-size: 16px;
                    font-weight: 700;
                    color: #0f172a;
                    margin-bottom: 4px;
                }

                .vcard-id-badge {
                    display: inline-block;
                    background: #f1f5f9;
                    border: 1px solid #cbd5e1;
                    color: #0f5e2e;
                    font-family: monospace;
                    font-weight: 700;
                    font-size: 11px;
                    padding: 2px 10px;
                    border-radius: 999px;
                    margin-bottom: 12px;
                }

                .vcard-grid {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                    text-align: left;
                    font-size: 11px;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 10px 12px;
                }

                .vcard-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .vlabel {
                    color: #64748b;
                }

                .vval {
                    font-weight: 600;
                    color: #0f172a;
                }

                .vstatus-badge {
                    font-size: 9px;
                    font-weight: 700;
                    padding: 2px 6px;
                    border-radius: 4px;
                    text-transform: uppercase;
                }

                .vstatus-badge.active {
                    background: #dcfce7;
                    color: #15803d;
                }

                .vstatus-badge.pending {
                    background: #fff7ed;
                    color: #c2410c;
                }

                .vcard-footer {
                    background: #f1f5f9;
                    border-top: 1px dashed #cbd5e1;
                    padding: 8px 10px;
                    font-size: 8.5px;
                    color: #64748b;
                }

                .vcard-auth {
                    font-weight: 700;
                    letter-spacing: 0.5px;
                }

                .vcard-barcode {
                    font-family: monospace;
                    letter-spacing: 3px;
                    font-size: 11px;
                    color: #334155;
                    margin-top: 2px;
                }
            </style>
        @endpush

    @section('media-print')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #virtualIdCard,
                #virtualIdCard * {
                    visibility: visible;
                }

                #virtualIdCard {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                }
            }
        </style>
    @endsection

@endsection
