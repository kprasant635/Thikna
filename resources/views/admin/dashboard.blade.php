@extends('layouts.admin')

@section('title', 'Admin Dashboard — SKOP-X')
@section('breadcrumb', 'Dashboard Overview')

@section('content')

    <div class="dashboard-header" style="margin-bottom: 24px;">
        <div class="dashboard-title-area">
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <span>📊 System Overview &amp; Metrics</span>
            </h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                Real-time platform insights, referral generation statistics, and course subscriptions
            </p>
        </div>
    </div>

    {{-- Metrics Grid: 5 Core Items + Overall Members --}}
    <div class="metrics-grid">
        
        {{-- 1. Total Referral Count --}}
        <div class="metric-card card-purple">
            <div class="metric-header">
                <span class="metric-label">Total Referrals</span>
                <div class="metric-icon-wrap">🤝</div>
            </div>
            <div class="metric-value">{{ number_format($totalReferralsCount) }}</div>
            <div class="metric-subtext">
                <a href="{{ route('admin.referrals') }}" style="color: #7c3aed; font-weight: 600; text-decoration: none;">
                    View referral tree &amp; directory →
                </a>
            </div>
        </div>

        {{-- 2. Total Active Users Count --}}
        <div class="metric-card card-green">
            <div class="metric-header">
                <span class="metric-label">Active Users</span>
                <div class="metric-icon-wrap">✓</div>
            </div>
            <div class="metric-value">{{ number_format($totalActiveUsersCount) }}</div>
            <div class="metric-subtext">
                <span style="color: #16a34a; font-weight: 600;">Subscribed</span> &amp; unlocked courses
            </div>
        </div>

        {{-- 3. Inactive Users Count --}}
        <div class="metric-card card-orange">
            <div class="metric-header">
                <span class="metric-label">Inactive Users</span>
                <div class="metric-icon-wrap">⚡</div>
            </div>
            <div class="metric-value">{{ number_format($totalInactiveUsersCount) }}</div>
            <div class="metric-subtext">
                <span>Pending subscription activation</span>
            </div>
        </div>

        {{-- 4. Course Count --}}
        <div class="metric-card card-blue">
            <div class="metric-header">
                <span class="metric-label">Course Count</span>
                <div class="metric-icon-wrap">📚</div>
            </div>
            <div class="metric-value">{{ number_format($totalCourseCount) }}</div>
            <div class="metric-subtext">
                <span>Available training modules</span>
            </div>
        </div>

        {{-- 5. Total Subscribe Count --}}
        <div class="metric-card card-cyan">
            <div class="metric-header">
                <span class="metric-label">Total Subscriptions</span>
                <div class="metric-icon-wrap">💳</div>
            </div>
            <div class="metric-value">{{ number_format($totalSubscribeCount) }}</div>
            <div class="metric-subtext">
                <span>Completed &amp; active purchases</span>
            </div>
        </div>

        {{-- Supplementary: Total Registered Members --}}
        <div class="metric-card card-indigo">
            <div class="metric-header">
                <span class="metric-label">Total Platform Users</span>
                <div class="metric-icon-wrap">👥</div>
            </div>
            <div class="metric-value">{{ number_format($totalUsersCount) }}</div>
            <div class="metric-subtext">
                <span>Total Revenue: ₹{{ number_format($totalRevenue, 2) }}</span>
            </div>
        </div>

    </div>

    {{-- Content Panels --}}
    <div class="dashboard-content-grid">

        {{-- Recent Users Table --}}
        <div class="panel-card">
            <div class="panel-header">
                <h3>
                    <span>👥</span>
                    <span>Recent Registrations</span>
                </h3>
                <span class="panel-badge">Latest 8 Members</span>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name / Mobile</th>
                            <th>Referral Code</th>
                            <th>Referred By</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentUsers as $user)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                    <div style="font-size: 11.5px; color: #64748b;">+91 {{ $user->phone ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 700; color: #1e3a8a;">
                                        {{ $user->referral_code ?? '—' }}
                                    </code>
                                </td>
                                <td>
                                    {{ $user->referrer ? $user->referrer->name : 'Direct' }}
                                </td>
                                <td>
                                    @if ($user->isActive())
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at->format('d M, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No users registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Subscriptions Table --}}
        <div class="panel-card">
            <div class="panel-header">
                <h3>
                    <span>💳</span>
                    <span>Recent Subscriptions</span>
                </h3>
                <span class="panel-badge">Latest 8 Orders</span>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentSubscriptions as $sub)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $sub->user->name ?? 'User #' . $sub->user_id }}</div>
                                    <div style="font-size: 11.5px; color: #64748b;">{{ $sub->user->phone ?? '' }}</div>
                                </td>
                                <td>
                                    <strong>₹{{ number_format($sub->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if ($sub->isActive())
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-pending">{{ ucfirst($sub->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $sub->products->count() }} Product(s)
                                </td>
                                <td>
                                    {{ $sub->created_at->format('d M, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No subscriptions created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .metric-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .metric-value {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .metric-subtext {
            font-size: 12.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .card-purple { border-top: 4px solid var(--purple); }
        .card-purple .metric-icon-wrap { background: #ede9fe; color: var(--purple); }

        .card-green { border-top: 4px solid var(--success); }
        .card-green .metric-icon-wrap { background: #dcfce7; color: var(--success); }

        .card-orange { border-top: 4px solid var(--warning); }
        .card-orange .metric-icon-wrap { background: #ffedd5; color: var(--warning); }

        .card-blue { border-top: 4px solid var(--primary-accent); }
        .card-blue .metric-icon-wrap { background: #dbeafe; color: var(--primary-accent); }

        .card-cyan { border-top: 4px solid var(--cyan); }
        .card-cyan .metric-icon-wrap { background: #cffafe; color: var(--cyan); }

        .card-indigo { border-top: 4px solid #4338ca; }
        .card-indigo .metric-icon-wrap { background: #e0e7ff; color: #4338ca; }

        .dashboard-content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .dashboard-content-grid {
                grid-template-columns: 1fr;
            }
        }

        .panel-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 14px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .panel-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-badge {
            font-size: 11.5px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .admin-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 12px 18px;
            border-bottom: 1px solid var(--card-border);
            white-space: nowrap;
        }

        .admin-table td {
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .status-active { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fff7ed; color: #c2410c; }

        .empty-state {
            padding: 36px 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 13.5px;
        }
    </style>
@endpush
