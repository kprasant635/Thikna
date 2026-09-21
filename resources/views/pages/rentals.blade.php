@extends('layouts.app')

@section('title', 'Home Rentals — SkopX')
@section('minimal-footer', true)

@section('content')

    <div class="wrap" style="padding-top:22px;">
        <div class="eyebrow-pill">🏠 {{ $city ?? 'Bhubaneswar' }} verified rental marketplace</div>
        <div class="results-head">
            <h1>{{ $city ?? 'Bhubaneswar' }} home rentals &amp; apartment listings</h1>
            <div class="results-count">{{ count($rentals) }}+ direct owner units · updated {{ $updatedAgo ?? '8 min' }} ago
            </div>
        </div>

        <form class="rental-search-bar" method="get">
            <div class="field"><label>Locality / Area</label><input type="text" name="area"
                    value="{{ request('area', $area ?? 'North Bhubaneswar') }}"></div>
            <div class="field"><label>Property Type</label>
                <select name="type">
                    @foreach ($propertyTypes ?? ['Apartment / Independent', 'Studio', 'Independent House'] as $t)
                        <option @selected(request('type') == $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>Bedrooms (BHK)</label>
                <select name="bhk">
                    @foreach ($bhkOptions ?? ['2 BHK & 3 BHK', '1 BHK', 'Any'] as $b)
                        <option @selected(request('bhk') == $b)>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>Budget Range</label>
                <select name="budget">
                    @foreach ($budgetOptions ?? ['₹8,000 – ₹25,000', 'Under ₹8,000', '₹25,000+'] as $bd)
                        <option @selected(request('budget') == $bd)>{{ $bd }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Search</button>
        </form>

        <div class="active-filters">
            @foreach ($activeFilters ?? ['Zero brokerage'] as $f)
                <span class="chip active">{{ $f }} ✕</span>
            @endforeach
            @foreach ($otherFilters ?? ['Furnished', 'Pet friendly', 'Immediate move-in', 'Gated society'] as $f)
                <span class="chip">{{ $f }}</span>
            @endforeach
        </div>
    </div>

    <div class="wrap section" style="padding-top:0;">
        <div class="rentals-layout">

            <section>
                <div class="section-sub" style="margin-bottom:14px;">Showing {{ count($rentals) }} prime verified
                    properties matching your preferences</div>

                @foreach ($rentals as $rental)
                    <a class="rental-item" href="{{ route('rentals.show', $rental['slug']) }}">
                        <div class="rental-thumb"><span
                                class="tag {{ $rental['tag_style'] ?? '' }}">{{ $rental['tag'] }}</span>
                            @if (!empty($rental['image']))
                                <img src="{{ asset($rental['image']) }}" alt="{{ $rental['title'] }}" class="thumb-img">
                            @else
                                {{ $rental['icon'] }}
                            @endif
                        </div>
                        <div class="rental-info">
                            <h3>{{ $rental['title'] }}</h3>
                            <div class="loc">📍 {{ $rental['location'] }}</div>
                            <div class="rental-price-row"><span
                                    class="amt">₹{{ number_format($rental['price']) }}</span><span class="per">/month
                                    · {{ $rental['deposit_label'] }}</span></div>
                            <div class="rental-facts">
                                @foreach ($rental['facts'] as $fact)
                                    <span>{{ $fact }}</span>
                                @endforeach
                            </div>
                            <div class="rental-tags">
                                @foreach ($rental['tags'] as $tag)
                                    <span>{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="rental-actions">
                            <div class="btn btn-primary">Call owner</div>
                            <div class="btn btn-gold">{{ $rental['secondary_action'] ?? 'Schedule visit' }}</div>
                        </div>
                    </a>
                @endforeach

                @if (is_object($rentals) && method_exists($rentals, 'links'))
                    <div class="pagination">
                        {{ $rentals->links() }}
                    </div>
                @endif
            </section>

            <aside>
                <div class="side-card">
                    <h4>🛡️ Zero brokerage guarantee</h4>
                    <p style="font-size:12.5px;color:var(--ink-soft);">Connect directly with owners and verified managers.
                        Save on traditional finder commissions on every lease.</p>
                    <div class="badge" style="margin-top:6px;">100% direct owners</div>
                </div>
                <div class="side-card" style="background:var(--green-tint);border:none;">
                    <div class="badge gold" style="margin-bottom:10px;">For property owners</div>
                    <h4>Post your rental property for free</h4>
                    <p style="font-size:12.5px;color:var(--ink-soft);">Reach thousands of monthly renters. Zero listing
                        fees, verified tenant screening.</p>
                    <a class="btn btn-gold" href="{{ route('business.create') }}"
                        style="display:block;text-align:center;margin-top:10px;">List property now</a>
                </div>
                <div class="side-card">
                    <h4>Average rent — {{ $city ?? 'Bhubaneswar' }} (2 BHK)</h4>
                    <div style="font-size:22px;font-family:'Fraunces',serif;color:var(--green-deep);">
                        ₹{{ number_format($avgRent ?? 15400) }}/mo</div>
                    <div style="font-size:12px;color:var(--ink-soft);">{{ $avgRentTrend ?? '−1.2% vs last year' }}</div>
                </div>
            </aside>

        </div>
    </div>

@endsection
