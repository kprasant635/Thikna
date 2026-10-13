@extends($layout ?? 'layouts.dashboard')

@section('title', 'Genealogy Tree — SkopX')
@section('breadcrumb', 'Genealogy Tree')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/genealogy.css') }}?v={{ file_exists(public_path('css/genealogy.css')) ? filemtime(public_path('css/genealogy.css')) : time() }}">
@endpush

@section('content')
<div class="gt-page-container" id="genealogyApp">

    <!-- ====================================================================
         1. TOP SECTION
         ==================================================================== -->
    <section class="gt-top-section">
        <!-- Header Row: Title & Breadcrumbs -->
        <div class="gt-header-row">
            <div class="gt-title-area">
                <h1 class="gt-page-title">
                    <span>🌳</span> Genealogy Tree
                </h1>
                <p class="gt-page-subtitle">
                    Explore your team hierarchy, direct referrals, and multilevel genealogy structure.
                </p>
            </div>

            <!-- Breadcrumbs -->
            <div class="gt-breadcrumb-wrap">
                <span style="color:var(--gt-text-muted);font-weight:600;">Trail:</span>
                <div id="gtBreadcrumbsWrap" style="display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap;">
                    <!-- Populated dynamically via JS -->
                    <span class="gt-bc-item active">{{ $currentRoot->name }}</span>
                </div>
                <button type="button" class="gt-btn-return-root" id="gtReturnRootBtn" style="display:none;" title="Return to original root member">
                    ↺ Return to My Tree
                </button>
            </div>
        </div>

        <!-- Controls Row: Search & Generations -->
        <div class="gt-controls-row">
            <!-- Member Search -->
            <div class="gt-search-wrapper">
                <div class="gt-search-input-box">
                    <span class="gt-search-icon">🔍</span>
                    <input type="text"
                           id="gtSearchInput"
                           class="gt-search-input"
                           placeholder="Search member by name, username, ID, or referral code…"
                           autocomplete="off">
                    <button type="button" id="gtSearchClear" class="gt-search-clear" title="Clear search">✕</button>
                    <div id="gtSearchSpinner" class="gt-search-spinner" title="Searching…"></div>
                </div>
                <!-- Search Results Dropdown -->
                <div id="gtSearchDropdown" class="gt-search-dropdown"></div>
            </div>

            <!-- Generation Selector -->
            <div class="gt-gen-selector-wrap">
                <span class="gt-gen-label">Generations:</span>
                <div class="gt-gen-buttons" id="gtGenButtons">
                    <button type="button" class="gt-gen-btn {{ $initialGenerations === 1 ? 'active' : '' }}" data-gen="1">1</button>
                    <button type="button" class="gt-gen-btn {{ $initialGenerations === 2 ? 'active' : '' }}" data-gen="2">2</button>
                    <button type="button" class="gt-gen-btn {{ $initialGenerations === 3 ? 'active' : '' }}" data-gen="3">3</button>
                    <button type="button" class="gt-gen-btn {{ $initialGenerations === 5 ? 'active' : '' }}" data-gen="5">5</button>
                    <button type="button" class="gt-gen-btn {{ $initialGenerations >= 10 ? 'active' : '' }}" data-gen="all">All</button>
                </div>
            </div>
        </div>

        <!-- Current Root Member Summary Bar -->
        <div class="gt-root-summary-bar" id="gtRootSummaryBar">
            <div class="gt-root-summary-left">
                <div class="gt-root-avatar-wrap">
                    @if($rootDetails['profile_photo_url'])
                        <img src="{{ $rootDetails['profile_photo_url'] }}" alt="{{ $rootDetails['name'] }}" class="gt-root-avatar">
                    @else
                        <div class="gt-root-avatar">{{ $rootDetails['initials'] }}</div>
                    @endif
                    <span class="gt-root-crown-badge" title="Root Member">👑</span>
                </div>
                <div class="gt-root-info">
                    <h3>
                        {{ $rootDetails['name'] }}
                        <span class="gt-status-pill {{ $rootDetails['status'] === 'active' ? 'status-active' : 'status-pending' }}">
                            <span class="gt-status-dot"></span> {{ ucfirst($rootDetails['status']) }}
                        </span>
                    </h3>
                    <div class="gt-root-info-sub">
                        <span><strong>ID:</strong> #{{ $rootDetails['id'] }}</span>
                        @if($rootDetails['username'])
                            <span><strong>User:</strong> &#64;{{ $rootDetails['username'] }}</span>
                        @endif
                        <span><strong>Referral Code:</strong> <code class="gt-pill-code">{{ $rootDetails['referral_code'] ?? '—' }}</code></span>
                        <span><strong>Direct Team:</strong> 👥 {{ $rootDetails['direct_referrals_count'] }} Members</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         2. TREE TOOLBAR
         ==================================================================== -->
    <div class="gt-toolbar">
        <div class="gt-toolbar-group">
            <button type="button" class="gt-btn-tool" id="gtBtnZoomIn" title="Zoom in (+15%)">
                <svg viewBox="0 0 20 20" fill="none"><path d="M10 5v10M5 10h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Zoom In
            </button>
            <button type="button" class="gt-btn-tool" id="gtBtnZoomOut" title="Zoom out (-15%)">
                <svg viewBox="0 0 20 20" fill="none"><path d="M5 10h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Zoom Out
            </button>
            <span class="gt-zoom-level-badge" id="gtZoomBadge">100%</span>

            <div class="gt-toolbar-divider"></div>

            <button type="button" class="gt-btn-tool" id="gtBtnFitScreen" title="Fit tree to screen">
                <svg viewBox="0 0 20 20" fill="none"><path d="M3 7V3h4M17 7V3h-4M3 13v4h4M17 13v4h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Fit Screen
            </button>
            <button type="button" class="gt-btn-tool" id="gtBtnResetView" title="Reset view position to root">
                <svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="2"/><circle cx="10" cy="10" r="2" fill="currentColor"/></svg>
                Reset Root
            </button>
        </div>

        <div class="gt-toolbar-group">
            <button type="button" class="gt-btn-tool" id="gtBtnExpandAll" title="Expand all branches">
                <svg viewBox="0 0 20 20" fill="none"><path d="M4 7l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Expand All
            </button>
            <button type="button" class="gt-btn-tool" id="gtBtnCollapseAll" title="Collapse branches">
                <svg viewBox="0 0 20 20" fill="none"><path d="M4 13l6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Collapse
            </button>

            <div class="gt-toolbar-divider"></div>

            <button type="button" class="gt-btn-tool" id="gtBtnRefresh" title="Refresh tree data">
                <svg viewBox="0 0 20 20" fill="none"><path d="M4 4v5h5M16 16v-5h-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 9A7 7 0 005.6 6.3L4 9m12 2l-1.6 2.7A7 7 0 014 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Refresh
            </button>
            <span class="gt-stats-badge" id="gtMemberCountBadge">
                Loading…
            </span>
        </div>
    </div>

    <!-- ====================================================================
         3. MAIN GENEALOGY CANVAS
         ==================================================================== -->
    <div class="gt-canvas-outer" id="gtCanvasOuter">
        <!-- Loading Overlay -->
        <div class="gt-loading-overlay" id="gtLoadingOverlay">
            <div class="gt-loader-spinner"></div>
            <span class="gt-loader-text">Loading Genealogy Hierarchy…</span>
        </div>

        <!-- Panzoom Viewport -->
        <div class="gt-panzoom-viewport" id="gtViewport">
            <!-- Dynamic Content Container -->
            <div class="gt-panzoom-content" id="gtContent">
                <!-- Tree will be rendered here by genealogy.js -->
            </div>
        </div>
    </div>

</div>

<!-- ====================================================================
     5. MEMBER DETAILS MODAL
     ==================================================================== -->
<div class="gt-modal-backdrop" id="gtModalBackdrop">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <button type="button" class="gt-modal-close-btn" id="gtModalCloseBtn" aria-label="Close modal">✕</button>
            <div id="gtModalAvatarBox">
                <div class="gt-modal-avatar">U</div>
            </div>
            <h3 class="gt-modal-name" id="gtModalName">Member Name</h3>
            <p class="gt-modal-username" id="gtModalUsername">@username</p>
            <div>
                <span class="gt-status-pill status-active" id="gtModalStatus">
                    <span class="gt-status-dot"></span> Active
                </span>
            </div>
        </div>

        <div class="gt-modal-body">
            <div class="gt-modal-grid">
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Member ID</span>
                    <span class="gt-modal-item-value" id="gtModalId">#—</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Referral Code</span>
                    <span class="gt-modal-item-value" id="gtModalRefCode">—</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Actual Referrer (Sponsor)</span>
                    <span class="gt-modal-item-value" id="gtModalReferrer">Loading…</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Visual Placement Parent</span>
                    <span class="gt-modal-item-value" id="gtModalVisualParent">Loading…</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Actual Direct Referrals</span>
                    <span class="gt-modal-item-value" id="gtModalDirectCount">— Members</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Visual Placements</span>
                    <span class="gt-modal-item-value" id="gtModalVisualCount">— / 10 Filled</span>
                </div>
            </div>

            <div class="gt-modal-grid" style="margin-top:-4px;">
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Placement Type</span>
                    <span class="gt-modal-item-value" id="gtModalPlacementType">Direct Placement</span>
                </div>
                <div class="gt-modal-item">
                    <span class="gt-modal-item-label">Registration Date</span>
                    <span class="gt-modal-item-value" id="gtModalJoiningDate">Loading…</span>
                </div>
            </div>
        </div>

        <div class="gt-modal-footer">
            <button type="button" class="gt-btn-secondary" id="gtModalCancelBtn">Close</button>
            <button type="button" class="gt-btn-primary" id="gtModalSetRootBtn">
                <span>🎯</span> Focus as Tree Root
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/genealogy.js') }}?v={{ file_exists(public_path('js/genealogy.js')) ? filemtime(public_path('js/genealogy.js')) : time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new GenealogyTreeApp({
                originalRootId: {{ $originalRoot->id }},
                currentRootId: {{ $currentRoot->id }},
                initialGenerations: {{ $initialGenerations }},
                isAdmin: {{ $isAdminView ? 'true' : 'false' }},
                treeApiUrl: "{{ route('dashboard.genealogy.tree') }}",
                childrenApiUrl: "{{ url('/dashboard/genealogy/node') }}",
                searchApiUrl: "{{ route('dashboard.genealogy.search') }}",
                memberApiUrl: "{{ url('/dashboard/genealogy/member') }}"
            });
        });
    </script>
@endpush
