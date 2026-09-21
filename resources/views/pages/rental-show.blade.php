@extends('layouts.app')

@section('title', ($property['title'] ?? 'Property Detail') . ' — SkopX Rentals')
@section('minimal-footer', true)

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-bar">
        <div class="wrap">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('rentals.index') }}">Home Rentals</a> /
            {{ $property['title'] ?? 'Property' }}
        </div>
    </div>

    <div class="wrap" style="padding-top:24px;">

        {{-- ── Hero photo + tag ── --}}
        <div class="rshow-hero">
            @if (!empty($property['image']))
                <img src="{{ asset($property['image']) }}" alt="{{ $property['title'] }}" class="rshow-hero-img">
            @else
                <div class="rshow-hero-placeholder">{{ $property['icon'] ?? '🏠' }}</div>
            @endif
            <span class="tag {{ $property['tag_style'] ?? '' }} rshow-tag">{{ $property['tag'] ?? 'Verified' }}</span>
        </div>

        <div class="rshow-layout">

            {{-- ══ LEFT — Property details ══ --}}
            <section class="rshow-main">

                {{-- Title + location --}}
                <h1 class="rshow-title">{{ $property['title'] }}</h1>
                <div class="rshow-loc">📍 {{ $property['location'] }}</div>

                {{-- Price row --}}
                <div class="rshow-price-row">
                    <span class="rshow-amt">₹{{ number_format($property['price']) }}</span>
                    <span class="rshow-per">/month</span>
                    <span class="rshow-deposit">· {{ $property['deposit_label'] ?? '1 month deposit' }}</span>
                </div>

                {{-- Facts strip --}}
                <div class="rshow-facts-strip">
                    @foreach ($property['facts'] ?? [] as $fact)
                        <div class="rshow-fact-item">{{ $fact }}</div>
                    @endforeach
                </div>

                {{-- Tags / highlights --}}
                @if (!empty($property['tags']))
                    <div class="rshow-tags">
                        @foreach ($property['tags'] as $tag)
                            <span class="chip">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- About section --}}
                <div class="rshow-section">
                    <h2>About this property</h2>
                    <p>
                        This {{ $property['title'] }} is a well-maintained unit in a prime Bhubaneswar neighbourhood.
                        Available for immediate occupancy with direct owner contact — no brokerage or hidden charges.
                        The property is
                        {{ strtolower(implode(', ', array_slice($property['tags'] ?? ['ready to move'], 0, 2))) }}.
                    </p>
                </div>

                {{-- Amenities --}}
                <div class="rshow-section">
                    <h2>Amenities included</h2>
                    <div class="rshow-amenities">
                        @foreach (['💡 24-hr electricity backup', '🚿 Hot & cold water supply', '🔒 Gated entry with security', '🅿️ Covered parking available', '📶 Fibre broadband ready', '🌿 Green common areas'] as $amenity)
                            <div class="rshow-amenity">{{ $amenity }}</div>
                        @endforeach
                    </div>
                </div>

                {{-- Map placeholder --}}
                <div class="rshow-section">
                    <h2>Location</h2>
                    <div class="map-box">🗺️ {{ $property['location'] }}</div>
                </div>

            </section>

            {{-- ══ RIGHT — Sidebar ══ --}}
            <aside class="rshow-sidebar">

                {{-- Schedule visit form --}}
                <div class="quote-box">
                    <h4>📅 Schedule a free visit</h4>
                    <form method="post" action="{{ route('rentals.index') }}">
                        @csrf
                        <div class="field" style="margin-bottom:10px;">
                            <label
                                style="font-size:11.5px;font-weight:700;color:var(--ink-soft);display:block;margin-bottom:4px;">Full
                                name</label>
                            <input type="text" name="name" placeholder="e.g. Priya Das"
                                style="width:100%;border:1.5px solid var(--line);border-radius:var(--radius-sm);padding:9px 11px;font-size:13.5px;font-family:'Inter',sans-serif;">
                        </div>
                        <div class="field" style="margin-bottom:10px;">
                            <label
                                style="font-size:11.5px;font-weight:700;color:var(--ink-soft);display:block;margin-bottom:4px;">Mobile
                                number</label>
                            <input type="tel" name="phone" placeholder="+91 98XXX XXXXX"
                                style="width:100%;border:1.5px solid var(--line);border-radius:var(--radius-sm);padding:9px 11px;font-size:13.5px;font-family:'Inter',sans-serif;">
                        </div>
                        <div class="field" style="margin-bottom:14px;">
                            <label
                                style="font-size:11.5px;font-weight:700;color:var(--ink-soft);display:block;margin-bottom:4px;">Preferred
                                visit date</label>
                            <input type="date" name="date"
                                style="width:100%;border:1.5px solid var(--line);border-radius:var(--radius-sm);padding:9px 11px;font-size:13.5px;font-family:'Inter',sans-serif;">
                        </div>
                        <button type="submit" class="btn btn-gold"
                            style="display:block;width:100%;padding:13px;font-size:14px;">
                            {{ $property['secondary_action'] ?? 'Schedule free visit' }}
                        </button>
                    </form>
                </div>

                {{-- Quick contact --}}
                <div class="side-card" style="text-align:center;">
                    <h4>Or call the owner directly</h4>
                    <a class="btn btn-primary" href="tel:+919800000000"
                        style="display:block;margin-top:10px;text-align:center;">
                        📞 Call owner
                    </a>
                    <a class="btn btn-outline" href="https://wa.me/919800000000" target="_blank" rel="noopener"
                        style="display:block;margin-top:8px;text-align:center;">
                        💬 WhatsApp
                    </a>
                    <p style="font-size:11.5px;color:var(--ink-soft);margin-top:10px;">
                        🔒 Zero brokerage — you pay no agent fee
                    </p>
                </div>

                {{-- Price summary --}}
                <div class="side-card">
                    <h4>💰 Cost summary</h4>
                    <div class="hours-row"><span>Monthly
                            rent</span><span><strong>₹{{ number_format($property['price']) }}</strong></span></div>
                    <div class="hours-row"><span>Security
                            deposit</span><span>{{ $property['deposit_label'] ?? '1 month' }}</span></div>
                    <div class="hours-row"><span>Brokerage</span><span
                            style="color:var(--green-bright);font-weight:700;">Zero ✓</span></div>
                    <div class="hours-row" style="border-top:2px solid var(--line);padding-top:8px;margin-top:4px;">
                        <span><strong>Move-in cost</strong></span>
                        <span><strong>₹{{ number_format($property['price'] * 2) }}</strong></span>
                    </div>
                </div>

            </aside>
        </div>
    </div>

@endsection
