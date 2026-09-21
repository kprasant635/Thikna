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
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #e0e7ff; color: #3730a3;">
                <span style="font-size: 20px;">👥</span>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Total Referrals</div>
                <div class="dash-stat-value" id="statTotalCount">{{ $referrals->count() }}</div>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #dcfce7; color: #15803d;">
                <span style="font-size: 20px;">✓</span>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Active / Subscribed</div>
                <div class="dash-stat-value" style="color: #15803d;">
                    {{ $referrals->filter(fn($r) => $r->isActive())->count() }}</div>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #fff7ed; color: #c2410c;">
                <span style="font-size: 20px;">⚡</span>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Pending / Unsubscribed</div>
                <div class="dash-stat-value" style="color: #c2410c;">
                    {{ $referrals->filter(fn($r) => !$r->isActive())->count() }}</div>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #fef3c7; color: #b45309;">
                <span style="font-size: 20px;">🔗</span>
            </div>
            <div class="dash-stat-info">
                <div class="dash-stat-label">Sub-Referrals Generated</div>
                <div class="dash-stat-value">{{ $referrals->sum('referrals_count') }}</div>
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
                                    data-certified="{{ $ref->isCertified() ? 'certified' : 'uncertified' }}">
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
                    <h4 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">No Referrals Yet</h4>
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
            function filterReferralsTable() {
                const searchInput = document.getElementById('referralSearchInput').value.toLowerCase().trim();
                const statusFilter = document.getElementById('referralStatusFilter').value;
                const table = document.getElementById('referralsTable');
                if (!table) return;

                const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                let visibleCount = 0;

                for (let i = 0; i < trs.length; i++) {
                    const tr = trs[i];
                    const rowText = tr.innerText.toLowerCase();
                    const rowStatus = tr.getAttribute('data-status');
                    const rowCertified = tr.getAttribute('data-certified');

                    let matchesSearch = searchInput === '' || rowText.includes(searchInput);
                    let matchesStatus = statusFilter === 'all' ||
                        (statusFilter === 'active' && rowStatus === 'active') ||
                        (statusFilter === 'pending' && rowStatus === 'pending') ||
                        (statusFilter === 'certified' && rowCertified === 'certified');

                    if (matchesSearch && matchesStatus) {
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
