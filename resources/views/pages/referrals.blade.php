@extends('layouts.dashboard')

@section('title', 'My Referral List — SkopX')

@section('content')

    <div class="dash-welcome-section">
        <div>
            <h1 class="dash-welcome-heading">👥 My Referral List</h1>
            <p class="dash-welcome-sub">View and track all members who registered using your referral code
                <strong>{{ Auth::user()->referral_code }}</strong>.
            </p>
        </div>
    </div>

    <!-- Referral Stats Overview Bar -->
    <div class="dash-stats-row" style="margin-bottom: 24px;">

        <!-- Total Referrals -->
        <div class="dash-stat-card stat-card-blue">
            <div class="dash-stat-icon">
                <!-- Users / Group -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="9" cy="8" r="3.5" fill="currentColor" />
                    <path d="M2.5 20c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5" fill="currentColor" opacity=".65" />
                    <circle cx="17" cy="9" r="2.5" fill="currentColor" opacity=".85" />
                    <path d="M16 14c2.8.3 5 2.7 5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        opacity=".65" />
                </svg>
            </div>

            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Referrals</div>
                <div class="dash-stat-value" id="statTotalCount">
                    {{ $referrals->count() }}
                </div>
            </div>
        </div>


        <!-- Active / Subscribed -->
        <div class="dash-stat-card stat-card-green">
            <div class="dash-stat-icon">
                <!-- Check Circle -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity=".25" />
                    <path d="M8 12l2.5 2.5L16.5 9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>

            <div class="dash-stat-info">
                <div class="dash-stat-label">Active / Subscribed / Paid</div>
                <div class="dash-stat-value">
                    {{ $referrals->filter(fn($r) => $r->isActive())->count() }}
                </div>
            </div>
        </div>


        <!-- Pending / Unsubscribed -->
        <div class="dash-stat-card stat-card-orange">
            <div class="dash-stat-icon">
                <!-- Clock / Pending -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity=".25" />
                    <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>

            <div class="dash-stat-info">
                <div class="dash-stat-label">Pending / Unsubscribed / Unpaid</div>
                <div class="dash-stat-value">
                    {{ $referrals->filter(fn($r) => !$r->isActive())->count() }}
                </div>
            </div>
        </div>


        <!-- Sub-Referrals -->
        <div class="dash-stat-card stat-card-purple">
            <div class="dash-stat-icon">
                <!-- Three Users -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="6" r="2.5" fill="currentColor" />
                    <circle cx="5" cy="9" r="2" fill="currentColor" />
                    <circle cx="19" cy="9" r="2" fill="currentColor" />

                    <path d="M7 19v-1.5a5 5 0 0 1 10 0V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M2 18v-1a3.5 3.5 0 0 1 4-3.45M22 18v-1a3.5 3.5 0 0 0-4-3.45" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>

            <div class="dash-stat-info">
                <div class="dash-stat-label">Sub-Referrals Generated / Team Added</div>
                <div class="dash-stat-value">
                    {{ $referrals->sum('referrals_count') }}
                </div>
            </div>
        </div>

    </div>

    <!-- Referral Table Card -->
    <div class="dash-card">
        <div class="dash-card-header"
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <h3>Members Referred by You</h3>

            <!-- Live Search & Filter Controls -->
            <div class="ref-table-controls">
                <div class="ref-search-box">
                    <span class="ref-search-icon">🔍</span>
                    <input type="text" id="referralSearchInput" onkeyup="filterReferralsTable()"
                        placeholder="Search by name, phone, date or status…">
                </div>
                <select id="referralStatusFilter" onchange="filterReferralsTable()" class="ref-status-select">
                    <option value="all">All Statuses</option>
                    <option value="active">Active (Subscribed)</option>
                    <option value="pending">Pending (Unsubscribed)</option>
                    <option value="certified">Certified</option>
                </select>

                <!-- Date Range Filter -->
                <select id="referralDatePreset" onchange="applyDatePreset(this.value)" class="ref-status-select">
                    <option value="all">All Dates</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last7">Last 7 Days</option>
                    <option value="last30">Last 30 Days</option>
                    <option value="thisMonth">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>

                <div class="ref-date-range-group" title="Filter by Joined Date Range">
                    <span class="ref-date-icon">📅</span>
                    <input type="date" id="referralStartDate" onchange="onDateInputChange()"
                        oninput="onDateInputChange()" class="ref-date-input" aria-label="Start Date">
                    <span class="ref-date-sep">to</span>
                    <input type="date" id="referralEndDate" onchange="onDateInputChange()"
                        oninput="onDateInputChange()" class="ref-date-input" aria-label="End Date">
                    <button type="button" id="referralDateClear" onclick="clearDateRangeFilter()"
                        class="ref-date-clear-btn" title="Clear Date Filter" style="display: none;">✕</button>
                </div>
            </div>
        </div>

        <div class="dash-card-body" style="padding: 0;">
            @if ($referrals->count() > 0)
                <div class="table-responsive">
                    <table class="referrals-table" id="referralsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member Name</th>
                                <th>Mobile Number</th>
                                <th>Joined Date</th>
                                <th>Subscription Status</th>
                                <th>Learning Status</th>
                                <th>Their Referrals Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $index => $ref)
                                @php
                                    $subCount = $ref->referrals_count ?? $ref->referrals()->count();
                                @endphp
                                <tr data-status="{{ $ref->isActive() ? 'active' : 'pending' }}"
                                    data-certified="{{ $ref->isCertified() ? 'certified' : 'uncertified' }}"
                                    data-date="{{ $ref->created_at->format('Y-m-d') }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="ref-user-cell">
                                            <div class="ref-user-avatar">
                                                @if ($ref->profilePhotoUrl())
                                                    <img src="{{ $ref->profilePhotoUrl() }}" alt="{{ $ref->name }}">
                                                @else
                                                    <span>{{ $ref->initials() }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $ref->name }}</strong>
                                                <div style="font-size: 11px; color: var(--ink-soft);">Code:
                                                    {{ $ref->referral_code ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span>
                                                +91 {{ substr($ref->phone, 0, 3) . '*****' . substr($ref->phone, -2) }}
                                            </span>

                                            <!-- Call -->
                                            <a href="tel:+91{{ $ref->phone }}" title="Call"
                                                class="contact-action call">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                    19.8 19.8 0 0 1-8.63-3.07
                                    19.5 19.5 0 0 1-6-6
                                    A19.8 19.8 0 0 1 2.12 4.18
                                    2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72
                                    c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.09 9.91
                                    a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45
                                    c.9.34 1.84.58 2.8.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                            </a>

                                            <!-- SMS -->
                                            <a href="sms:+91{{ $ref->phone }}" title="Message"
                                                class="contact-action message">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8
                                    8.5 8.5 0 0 1-7.6 4.7
                                    8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7
                                    a8.4 8.4 0 0 1-.9-3.8
                                    8.5 8.5 0 0 1 4.7-7.6
                                    8.4 8.4 0 0 1 3.8-.9h.5
                                    a8.5 8.5 0 0 1 8 8z" />
                                                </svg>
                                            </a>

                                            <!-- WhatsApp -->
                                            <a href="https://wa.me/91{{ $ref->phone }}" target="_blank"
                                                rel="noopener noreferrer" title="WhatsApp"
                                                class="contact-action whatsapp">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 20l1.2-5.3
                                    A8.4 8.4 0 1 1 21 11.5z" />
                                                    <path d="M9 9a1 1 0 0 1 1-1l1 2-1 1
                                    c.7 1.5 1.5 2.3 3 3l1-1 2 1
                                    a1 1 0 0 1-1 1.5c-3.5 0-7.5-4-7.5-6.5" />
                                                </svg>
                                            </a>

                                            <!-- Share -->
                                            <button type="button" title="Share Contact" class="contact-action share"
                                                onclick="shareContact('{{ $ref->phone }}')">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <circle cx="18" cy="5" r="3" />
                                                    <circle cx="6" cy="12" r="3" />
                                                    <circle cx="18" cy="19" r="3" />
                                                    <path d="m8.7 10.7 6.6-4.4M8.7 13.3l6.6 4.4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
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
                                        <span class="ref-count-pill" title="Has referred {{ $subCount }} member(s)">
                                            👥 <strong>{{ $subCount }}</strong> referral(s)
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="noResultsMsg" style="display: none; padding: 30px; text-align: center; color: #64748b;">
                    🔍 No referrals match your search criteria.
                </div>
            @else
                <div class="dash-empty-state" style="padding: 40px 20px; text-align: center;">
                    <div class="dash-empty-icon" style="font-size: 40px; margin-bottom: 12px;">🤝</div>
                    <h4 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">No Referrals Yet
                    </h4>
                    <p style="font-size: 14px; color: #64748b; margin-bottom: 18px;">Share your unique referral code
                        <strong>{{ Auth::user()->referral_code }}</strong> with friends to invite them!
                    </p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"
                        style="padding: 10px 20px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                        📋 Get Referral Link
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function shareContact(phone) {
                const fullPhone = '+91' + phone;

                if (navigator.share) {
                    navigator.share({
                        title: 'Contact',
                        text: 'Contact number: ' + fullPhone
                    }).catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Sharing failed:', error);
                        }
                    });
                } else if (navigator.clipboard) {
                    navigator.clipboard.writeText(fullPhone)
                        .then(() => alert('Phone number copied!'))
                        .catch(() => alert('Unable to copy phone number.'));
                } else {
                    alert('Sharing is not supported in this browser.');
                }
            }

            function formatDateToYMD(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function applyDatePreset(presetValue) {
                const startInput = document.getElementById('referralStartDate');
                const endInput = document.getElementById('referralEndDate');
                if (!startInput || !endInput) return;

                const now = new Date();
                const todayStr = formatDateToYMD(now);

                if (presetValue === 'all') {
                    startInput.value = '';
                    endInput.value = '';
                } else if (presetValue === 'today') {
                    startInput.value = todayStr;
                    endInput.value = todayStr;
                } else if (presetValue === 'yesterday') {
                    const yesterday = new Date();
                    yesterday.setDate(now.getDate() - 1);
                    const yestStr = formatDateToYMD(yesterday);
                    startInput.value = yestStr;
                    endInput.value = yestStr;
                } else if (presetValue === 'last7') {
                    const past7 = new Date();
                    past7.setDate(now.getDate() - 6);
                    startInput.value = formatDateToYMD(past7);
                    endInput.value = todayStr;
                } else if (presetValue === 'last30') {
                    const past30 = new Date();
                    past30.setDate(now.getDate() - 29);
                    startInput.value = formatDateToYMD(past30);
                    endInput.value = todayStr;
                } else if (presetValue === 'thisMonth') {
                    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                    startInput.value = formatDateToYMD(firstDay);
                    endInput.value = todayStr;
                }

                filterReferralsTable();
            }

            function onDateInputChange() {
                const preset = document.getElementById('referralDatePreset');
                const start = document.getElementById('referralStartDate').value;
                const end = document.getElementById('referralEndDate').value;

                if (preset) {
                    if (!start && !end) {
                        preset.value = 'all';
                    } else {
                        preset.value = 'custom';
                    }
                }
                filterReferralsTable();
            }

            function clearDateRangeFilter() {
                const startInput = document.getElementById('referralStartDate');
                const endInput = document.getElementById('referralEndDate');
                const preset = document.getElementById('referralDatePreset');

                if (startInput) startInput.value = '';
                if (endInput) endInput.value = '';
                if (preset) preset.value = 'all';

                filterReferralsTable();
            }

            function filterReferralsTable() {
                const searchInput = document.getElementById('referralSearchInput').value.toLowerCase().trim();
                const statusFilter = document.getElementById('referralStatusFilter').value;
                const startDate = document.getElementById('referralStartDate')?.value || '';
                const endDate = document.getElementById('referralEndDate')?.value || '';
                const clearBtn = document.getElementById('referralDateClear');

                if (clearBtn) {
                    clearBtn.style.display = (startDate || endDate) ? 'inline-flex' : 'none';
                }

                const table = document.getElementById('referralsTable');
                if (!table) return;

                const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                let visibleCount = 0;

                for (let i = 0; i < trs.length; i++) {
                    const tr = trs[i];
                    const rowText = tr.innerText.toLowerCase();
                    const rowStatus = tr.getAttribute('data-status');
                    const rowCertified = tr.getAttribute('data-certified');
                    const rowDate = tr.getAttribute('data-date');

                    let matchesSearch = searchInput === '' || rowText.includes(searchInput);
                    let matchesStatus = statusFilter === 'all' ||
                        (statusFilter === 'active' && rowStatus === 'active') ||
                        (statusFilter === 'pending' && rowStatus === 'pending') ||
                        (statusFilter === 'certified' && rowCertified === 'certified');

                    let matchesDate = true;
                    if (startDate && rowDate && rowDate < startDate) {
                        matchesDate = false;
                    }
                    if (endDate && rowDate && rowDate > endDate) {
                        matchesDate = false;
                    }

                    if (matchesSearch && matchesStatus && matchesDate) {
                        tr.style.display = '';
                        visibleCount++;
                    } else {
                        tr.style.display = 'none';
                    }
                }

                const noResults = document.getElementById('noResultsMsg');
                if (noResults) {
                    noResults.style.display = (visibleCount === 0 && trs.length > 0) ? 'block' : 'none';
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .contact-action {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: none;
                border-radius: 6px;
                text-decoration: none;
                cursor: pointer;
                transition: opacity 0.2s ease;
            }

            .contact-action:hover {
                opacity: 0.8;
            }

            .contact-action.call {
                color: #198754;
                background: #e8f5ee;
            }

            .contact-action.message {
                color: #0d6efd;
                background: #eaf2ff;
            }

            .contact-action.whatsapp {
                color: #128c4a;
                background: #e4f7eb;
            }

            .contact-action.share {
                color: #495057;
                background: #edf0f2;
            }

            .contact-action svg {
                display: block;
                flex-shrink: 0;
            }


            .ref-table-controls {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .ref-search-box {
                position: relative;
                display: flex;
                align-items: center;
            }

            .ref-search-icon {
                position: absolute;
                left: 12px;
                font-size: 13px;
                color: #94a3b8;
                pointer-events: none;
            }

            .ref-search-box input {
                padding: 8px 14px 8px 34px;
                border: 1.5px solid var(--line);
                border-radius: var(--radius-sm);
                font-size: 13px;
                width: 260px;
                background: #ffffff;
            }

            .ref-search-box input:focus {
                outline: none;
                border-color: var(--green-deep);
                box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
            }

            .ref-status-select {
                padding: 8px 12px;
                border: 1.5px solid var(--line);
                border-radius: var(--radius-sm);
                font-size: 13px;
                background: #ffffff;
                color: var(--ink);
                cursor: pointer;
            }

            .ref-status-select:focus {
                outline: none;
                border-color: var(--green-deep);
                box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
            }

            .ref-date-range-group {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #ffffff;
                border: 1.5px solid var(--line);
                border-radius: var(--radius-sm);
                padding: 4px 10px;
                position: relative;
            }

            .ref-date-range-group:focus-within {
                border-color: var(--green-deep);
                box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
            }

            .ref-date-icon {
                font-size: 13px;
                color: #64748b;
                display: flex;
                align-items: center;
                user-select: none;
            }

            .ref-date-input {
                border: none;
                background: transparent;
                font-size: 12.5px;
                color: var(--ink);
                font-family: inherit;
                padding: 3px 0;
                outline: none;
                cursor: pointer;
            }

            .ref-date-sep {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 500;
                user-select: none;
                padding: 0 2px;
            }

            .ref-date-clear-btn {
                background: #f1f5f9;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                color: #64748b;
                cursor: pointer;
                transition: background 0.15s, color 0.15s;
                margin-left: 2px;
                padding: 0;
            }

            .ref-date-clear-btn:hover {
                background: #fee2e2;
                color: #ef4444;
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
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #0f5e2e;
                color: white;
                font-weight: bold;
                font-size: 14px;
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
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 12px;
                color: #334155;
            }

            .table-responsive {
                overflow-x: auto;
            }
        </style>
    @endpush

@endsection
