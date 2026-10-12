@extends('layouts.admin')

@section('title', 'Total Referrals List — SKOP-X Admin')
@section('breadcrumb', 'Total Referral List')

@section('content')

    {{-- Page Header --}}
    <div class="ref-page-header">
        <div>
            <h1 class="ref-page-title">
                <span>🤝</span>
                <span>Total Referral Directory</span>
            </h1>
            <p class="ref-page-subtitle">
                Complete audit of referred members, who referred whom, registration timestamps, and subscription activation status.
            </p>
        </div>
    </div>

    {{-- Quick Stat Summary Cards --}}
    <div class="ref-summary-grid">
        <div class="ref-stat-card border-purple">
            <div class="ref-stat-label">Total Referrals</div>
            <div class="ref-stat-value text-purple">{{ number_format($totalReferralsCount) }}</div>
            <div class="ref-stat-desc">Members registered via invite links</div>
        </div>

        <div class="ref-stat-card border-green">
            <div class="ref-stat-label">Subscribed (Active)</div>
            <div class="ref-stat-value text-green">{{ number_format($subscribedCount) }}</div>
            <div class="ref-stat-desc">Purchased courses &amp; unlocked rewards</div>
        </div>

        <div class="ref-stat-card border-orange">
            <div class="ref-stat-label">Unsubscribed (Pending)</div>
            <div class="ref-stat-value text-orange">{{ number_format($pendingCount) }}</div>
            <div class="ref-stat-desc">Awaiting first course subscription</div>
        </div>

        <div class="ref-stat-card border-cyan">
            <div class="ref-stat-label">Subscription Rate</div>
            <div class="ref-stat-value text-cyan">{{ $conversionRate }}%</div>
            <div class="ref-stat-desc">{{ number_format($certifiedCount) }} members certified</div>
        </div>
    </div>

    {{-- Main Table Panel --}}
    <div class="admin-panel-card">
        
        {{-- Filters & Controls Bar --}}
        <div class="admin-panel-controls">
            <div class="controls-left">
                <!-- Search Box -->
                <div class="ref-search-box">
                    <span class="ref-search-icon">🔍</span>
                    <input type="text" id="referralSearchInput" onkeyup="filterAdminReferralsTable()"
                        placeholder="Search by member, phone, email, referrer, code…">
                </div>

                <!-- Subscription Status Filter -->
                <select id="referralStatusFilter" onchange="filterAdminReferralsTable()" class="ref-filter-select">
                    <option value="all">All Subscription Statuses</option>
                    <option value="active">Subscribed (Active)</option>
                    <option value="pending">Unsubscribed (Pending)</option>
                </select>

                <!-- Certification Filter -->
                <select id="referralCertifiedFilter" onchange="filterAdminReferralsTable()" class="ref-filter-select">
                    <option value="all">All Learning Statuses</option>
                    <option value="certified">Certified Only</option>
                    <option value="in_progress">In Progress</option>
                </select>

                <!-- Date Preset Filter -->
                <select id="referralDatePreset" onchange="applyDatePreset(this.value)" class="ref-filter-select">
                    <option value="all">All Registration Dates</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last7">Last 7 Days</option>
                    <option value="last30">Last 30 Days</option>
                    <option value="thisMonth">This Month</option>
                    <option value="custom">Custom Date Range</option>
                </select>

                <!-- Date Range Calendar Group -->
                <div class="ref-date-range-group" title="Filter by registration date range">
                    <span class="ref-date-icon">📅</span>
                    <input type="date" id="referralStartDate" onchange="onDateInputChange()" oninput="onDateInputChange()" class="ref-date-input" aria-label="Start Date">
                    <span class="ref-date-sep">to</span>
                    <input type="date" id="referralEndDate" onchange="onDateInputChange()" oninput="onDateInputChange()" class="ref-date-input" aria-label="End Date">
                    <button type="button" id="referralDateClear" onclick="clearDateRangeFilter()" class="ref-date-clear-btn" title="Clear Date Filter" style="display: none;">✕</button>
                </div>
            </div>

            <div class="controls-right">
                <span class="results-badge" id="resultsCountBadge">
                    Showing {{ $referrals->count() }} referral(s)
                </span>
            </div>
        </div>

        {{-- Referrals Data Table --}}
        <div class="table-responsive">
            <table class="admin-referrals-table" id="adminReferralsTable">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th>Referred Member Details</th>
                        <th>Contact Options</th>
                        <th>Referred By (Who)</th>
                        <th>Registered Date</th>
                        <th>Subscribe Status</th>
                        <th>Learning Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referrals as $index => $ref)
                        @php
                            $sub = $ref->subscriptions->where('status', 'active')->first();
                        @endphp
                        <tr data-status="{{ $ref->isActive() ? 'active' : 'pending' }}"
                            data-certified="{{ $ref->isCertified() ? 'certified' : 'in_progress' }}"
                            data-date="{{ $ref->created_at->format('Y-m-d') }}">
                            
                            {{-- Index --}}
                            <td class="row-index">
                                {{ $index + 1 }}
                            </td>

                            {{-- Referred Member Info --}}
                            <td>
                                <div class="member-cell">
                                    <div class="member-avatar">
                                        {{ $ref->initials() }}
                                    </div>
                                    <div>
                                        <div class="member-name">{{ $ref->name }}</div>
                                        <div class="member-meta">
                                            <span>Code:</span>
                                            <code class="member-code">{{ $ref->referral_code ?? '—' }}</code>
                                        </div>
                                        @if ($ref->address)
                                            <div class="member-address">📍 {{ $ref->address }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Contact Options (Call, WhatsApp, SMS, Email) --}}
                            <td>
                                <div class="contact-cell">
                                    <div class="contact-phone">
                                        +91 {{ $ref->phone ?? 'Not provided' }}
                                    </div>

                                    @if ($ref->phone)
                                        <div class="contact-actions-row">
                                            <a href="tel:+91{{ $ref->phone }}" class="action-btn call" title="Call Member">
                                                📞
                                            </a>
                                            <a href="https://wa.me/91{{ $ref->phone }}" target="_blank" rel="noopener noreferrer" class="action-btn whatsapp" title="Chat on WhatsApp">
                                                💬
                                            </a>
                                            <a href="sms:+91{{ $ref->phone }}" class="action-btn sms" title="Send SMS">
                                                ✉️
                                            </a>
                                            @if ($ref->email)
                                                <a href="mailto:{{ $ref->email }}" class="action-btn email" title="Send Email">
                                                    📧
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($ref->email)
                                        <div class="contact-email">{{ $ref->email }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Referred By Whom --}}
                            <td>
                                @if ($ref->referrer)
                                    <div class="referrer-cell">
                                        <div class="referrer-name">
                                            👤 {{ $ref->referrer->name }}
                                        </div>
                                        <div class="referrer-phone">
                                            +91 {{ $ref->referrer->phone ?? '—' }}
                                        </div>
                                        <div class="referrer-code">
                                            Code: <code>{{ $ref->referrer->referral_code ?? '—' }}</code>
                                        </div>
                                    </div>
                                @else
                                    <span class="direct-pill">Direct (No Referrer)</span>
                                @endif
                            </td>

                            {{-- When Registered --}}
                            <td>
                                <div class="date-cell">
                                    <div class="date-primary">
                                        {{ $ref->created_at->format('d M Y') }}
                                    </div>
                                    <div class="date-time">
                                        {{ $ref->created_at->format('h:i A') }}
                                    </div>
                                    <div class="date-relative">
                                        ({{ $ref->created_at->diffForHumans() }})
                                    </div>
                                </div>
                            </td>

                            {{-- Subscribed or Not --}}
                            <td>
                                @if ($ref->isActive())
                                    <span class="badge-sub badge-subscribed">
                                        ✓ Subscribed (Active)
                                    </span>
                                    @if ($sub)
                                        <div class="sub-detail-text">
                                            Paid: ₹{{ number_format($sub->amount, 2) }}
                                        </div>
                                    @endif
                                @else
                                    <span class="badge-sub badge-unsubscribed">
                                        ⚡ Unsubscribed (Pending)
                                    </span>
                                    <div class="sub-detail-text">No courses purchased</div>
                                @endif
                            </td>

                            {{-- Learning Status --}}
                            <td>
                                @if ($ref->isCertified())
                                    <span class="badge-learning badge-certified">
                                        🏆 Certified
                                    </span>
                                @else
                                    <span class="badge-learning badge-progress">
                                        📖 In Progress
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-referrals-box">
                                <div style="font-size: 32px; margin-bottom: 10px;">🤝</div>
                                <h3>No Referrals Found</h3>
                                <p>There are no members registered through referral codes yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- No matching filter results message --}}
        <div id="noResultsMsg" style="display: none; padding: 40px 20px; text-align: center; color: #64748b;">
            <div style="font-size: 28px; margin-bottom: 8px;">🔍</div>
            <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No matching referrals</h4>
            <p style="font-size: 13.5px;">Try adjusting your search terms or clearing the date/status filter criteria.</p>
            <button type="button" onclick="resetAllFilters()" style="margin-top: 12px; background: #2563eb; color: #ffffff; border: none; padding: 6px 14px; border-radius: 6px; font-size: 12.5px; cursor: pointer;">
                Reset All Filters
            </button>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
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

            filterAdminReferralsTable();
        }

        function onDateInputChange() {
            const preset = document.getElementById('referralDatePreset');
            const start = document.getElementById('referralStartDate').value;
            const end = document.getElementById('referralEndDate').value;

            if (preset) {
                preset.value = (!start && !end) ? 'all' : 'custom';
            }
            filterAdminReferralsTable();
        }

        function clearDateRangeFilter() {
            const startInput = document.getElementById('referralStartDate');
            const endInput = document.getElementById('referralEndDate');
            const preset = document.getElementById('referralDatePreset');

            if (startInput) startInput.value = '';
            if (endInput) endInput.value = '';
            if (preset) preset.value = 'all';

            filterAdminReferralsTable();
        }

        function resetAllFilters() {
            document.getElementById('referralSearchInput').value = '';
            document.getElementById('referralStatusFilter').value = 'all';
            document.getElementById('referralCertifiedFilter').value = 'all';
            clearDateRangeFilter();
        }

        function filterAdminReferralsTable() {
            const searchInput = document.getElementById('referralSearchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('referralStatusFilter').value;
            const certifiedFilter = document.getElementById('referralCertifiedFilter').value;
            const startDate = document.getElementById('referralStartDate')?.value || '';
            const endDate = document.getElementById('referralEndDate')?.value || '';
            const clearBtn = document.getElementById('referralDateClear');
            const countBadge = document.getElementById('resultsCountBadge');

            if (clearBtn) {
                clearBtn.style.display = (startDate || endDate) ? 'inline-flex' : 'none';
            }

            const table = document.getElementById('adminReferralsTable');
            if (!table) return;

            const tbody = table.getElementsByTagName('tbody')[0];
            if (!tbody) return;

            const trs = tbody.getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 0; i < trs.length; i++) {
                const tr = trs[i];
                const rowDate = tr.getAttribute('data-date');
                if (!rowDate) continue; // skip empty state row

                const rowText = tr.innerText.toLowerCase();
                const rowStatus = tr.getAttribute('data-status');
                const rowCertified = tr.getAttribute('data-certified');

                let matchesSearch = searchInput === '' || rowText.includes(searchInput);
                let matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;
                let matchesCertified = certifiedFilter === 'all' || rowCertified === certifiedFilter;

                let matchesDate = true;
                if (startDate && rowDate < startDate) matchesDate = false;
                if (endDate && rowDate > endDate) matchesDate = false;

                if (matchesSearch && matchesStatus && matchesCertified && matchesDate) {
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

            if (countBadge) {
                countBadge.textContent = `Showing ${visibleCount} referral(s)`;
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        .ref-page-header {
            margin-bottom: 24px;
        }

        .ref-page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ref-page-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        /* Stat Summary Grid */
        .ref-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 26px;
        }

        .ref-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .ref-stat-card.border-purple { border-top: 4px solid #7c3aed; }
        .ref-stat-card.border-green { border-top: 4px solid #16a34a; }
        .ref-stat-card.border-orange { border-top: 4px solid #ea580c; }
        .ref-stat-card.border-cyan { border-top: 4px solid #0891b2; }

        .ref-stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .ref-stat-value {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .text-purple { color: #7c3aed; }
        .text-green { color: #16a34a; }
        .text-orange { color: #ea580c; }
        .text-cyan { color: #0891b2; }

        .ref-stat-desc {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Admin Panel Card */
        .admin-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .admin-panel-controls {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .controls-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
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
            padding: 8px 12px 8px 34px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            width: 250px;
            background: #ffffff;
            outline: none;
        }

        .ref-search-box input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .ref-filter-select {
            padding: 8px 12px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            background: #ffffff;
            color: #1e293b;
            cursor: pointer;
            outline: none;
        }

        .ref-filter-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .ref-date-range-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 4px 10px;
        }

        .ref-date-range-group:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .ref-date-icon {
            font-size: 13px;
            color: #64748b;
        }

        .ref-date-input {
            border: none;
            background: transparent;
            font-size: 12.5px;
            color: #1e293b;
            font-family: inherit;
            padding: 3px 0;
            outline: none;
            cursor: pointer;
        }

        .ref-date-sep {
            font-size: 12px;
            color: #94a3b8;
        }

        .ref-date-clear-btn {
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #64748b;
            cursor: pointer;
            margin-left: 2px;
        }

        .ref-date-clear-btn:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .results-badge {
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 6px;
            white-space: nowrap;
        }

        /* Referrals Table */
        .table-responsive {
            overflow-x: auto;
        }

        .admin-referrals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .admin-referrals-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .admin-referrals-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }

        .admin-referrals-table tr:hover {
            background: #f8fafc;
        }

        .row-index {
            color: #94a3b8;
            font-weight: 600;
        }

        /* Member Cell */
        .member-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 200px;
        }

        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .member-name {
            font-weight: 700;
            color: #0f172a;
        }

        .member-meta {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .member-code {
            background: #f1f5f9;
            color: #1e3a8a;
            padding: 1px 5px;
            border-radius: 4px;
            font-weight: 700;
        }

        .member-address {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Contact Cell */
        .contact-cell {
            min-width: 180px;
        }

        .contact-phone {
            font-weight: 600;
            color: #1e293b;
        }

        .contact-actions-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 4px 0;
        }

        .action-btn {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-decoration: none;
            background: #f1f5f9;
            transition: transform 0.1s, background 0.15s;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn.call:hover { background: #dcfce7; }
        .action-btn.whatsapp:hover { background: #d1fae5; }
        .action-btn.sms:hover { background: #dbeafe; }
        .action-btn.email:hover { background: #fee2e2; }

        .contact-email {
            font-size: 11.5px;
            color: #64748b;
        }

        /* Referrer Cell */
        .referrer-cell {
            min-width: 170px;
        }

        .referrer-name {
            font-weight: 700;
            color: #0f172a;
        }

        .referrer-phone {
            font-size: 11.5px;
            color: #64748b;
        }

        .referrer-code {
            font-size: 11px;
            color: #64748b;
            margin-top: 1px;
        }

        .direct-pill {
            display: inline-block;
            font-size: 11.5px;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Date Cell */
        .date-cell {
            min-width: 130px;
        }

        .date-primary {
            font-weight: 600;
            color: #1e293b;
        }

        .date-time {
            font-size: 11.5px;
            color: #64748b;
        }

        .date-relative {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Badges */
        .badge-sub {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-subscribed {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-unsubscribed {
            background: #fff7ed;
            color: #c2410c;
        }

        .sub-detail-text {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }

        .badge-learning {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-certified {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-progress {
            background: #f1f5f9;
            color: #64748b;
        }

        .empty-referrals-box {
            padding: 60px 20px;
            text-align: center;
            color: #64748b;
        }
    </style>
@endpush
