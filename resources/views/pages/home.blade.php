@extends('layouts.app')

@section('title', 'Thikana — Find shops, rentals & products near you')

@section('content')

<div class="wrap hero">
  <div class="eyebrow-pill">📍 Kamrup District #1 Local Directory</div>
  <h1>Find what you need in <span class="accent">{{ $city ?? 'Guwahati' }}</span> — shops, rentals, products &amp; services</h1>
  <p>Connect directly with verified local shops, homeowners and sellers — no middlemen, no listing fees.</p>

  <div class="trust-row">
    <div class="trust-item"><div class="trust-icon">☎️</div><div><span class="num">100%</span>Verified phones</div></div>
    <div class="trust-item"><div class="trust-icon">💰</div><div><span class="num">Zero</span>Commission</div></div>
    <div class="trust-item"><div class="trust-icon">⭐</div><div><span class="num">{{ number_format($reviewCount ?? 18000) }}+</span>Reviews</div></div>
    <div class="trust-item"><div class="trust-icon">⏱️</div><div><span class="num">&lt; 15 min</span>Response time</div></div>
  </div>
</div>

<div class="wrap section">
  <div class="section-head">
    <div>
      <h2>Explore our 3 marketplaces</h2>
      <div class="section-sub">Active inventory updated throughout the day</div>
    </div>
  </div>
  <div class="sector-grid">
    @foreach($sectors as $sector)
    <a class="sector-card" href="{{ $sector['url'] }}">
      <div class="sector-icon">{{ $sector['icon'] }}</div>
      <div class="count">{{ $sector['count'] }}</div>
      <h3>{{ $sector['title'] }}</h3>
      <p>{{ $sector['description'] }}</p>
      <div class="go">{{ $sector['cta'] }} →</div>
    </a>
    @endforeach
  </div>
</div>

<div class="wrap section">
  <div class="section-head">
    <div>
      <h2>Featured merchants near you</h2>
      <div class="section-sub">Hand-inspected credentials · sorted by response speed</div>
    </div>
    <a class="see-all" href="{{ route('shops.index') }}">See all shops →</a>
  </div>

  @foreach($featuredMerchants as $merchant)
  <a class="merchant-card" href="{{ route('business.show', ['business' => $merchant['slug']]) }}">
    <div class="merchant-thumb"><span class="tag">{{ $merchant['status'] }}</span>{{ $merchant['icon'] }}</div>
    <div class="merchant-body">
      <h3>{{ $merchant['name'] }}</h3>
      <div class="merchant-meta">
        <span class="rating"><span class="star">★</span> {{ $merchant['rating'] }}</span>
        <span>{{ $merchant['reviews'] }} verified reviews</span>
        <span>· {{ $merchant['location'] }}</span>
      </div>
      <div class="merchant-desc">{{ $merchant['description'] }}</div>
      <div class="merchant-tags">
        @foreach($merchant['tags'] as $tag)<span>{{ $tag }}</span>@endforeach
      </div>
    </div>
    <div class="merchant-actions">
      <div class="btn btn-primary">Call now</div>
      <div class="btn btn-gold">Get best quote</div>
    </div>
  </a>
  @endforeach
</div>

<div class="wrap section">
  <div class="section-head">
    <div>
      <h2>Homes for rent</h2>
      <div class="section-sub">1BHK to 3BHK, verified owners, zero-brokerage listings marked</div>
    </div>
    <a class="see-all" href="{{ route('rentals.index') }}">See all rentals →</a>
  </div>
  <div class="card-row">
    @foreach($featuredRentals as $rental)
    <a class="card" href="{{ route('rentals.index') }}">
      <div class="card-img">{{ $rental['icon'] }}</div>
      <div class="card-body">
        <span class="card-tag">{{ $rental['tag'] }}</span>
        <h3>{{ $rental['title'] }}</h3>
        <div class="loc">{{ $rental['location'] }}</div>
        <div class="card-meta"><div class="price">₹{{ number_format($rental['price']) }}/mo</div><div class="btn btn-tint">View</div></div>
      </div>
    </a>
    @endforeach
  </div>
</div>

<div class="wrap section">
  <div class="section-head">
    <div>
      <h2>Products from local sellers</h2>
      <div class="section-sub">Buy directly from shops and individuals near you</div>
    </div>
    <a class="see-all" href="{{ route('business.show', ['business' => 'apex-tech-mobile-hub']) }}">See all products →</a>
  </div>
  <div class="card-row">
    @foreach($featuredProducts as $product)
    <a class="card" href="{{ route('business.show', ['business' => $product['seller_slug']]) }}">
      <div class="card-img">{{ $product['icon'] }}</div>
      <div class="card-body">
        <span class="card-tag">{{ $product['category'] }}</span>
        <h3>{{ $product['name'] }}</h3>
        <div class="loc">Sold by {{ $product['seller'] }}</div>
        <div class="card-meta"><div class="price">₹{{ number_format($product['price']) }}</div><div class="btn btn-tint">Buy now</div></div>
      </div>
    </a>
    @endforeach
  </div>
</div>

<div class="wrap section">
  <div class="deals-band">
    <h2 style="font-size:20px;">Exclusive deals of the day</h2>
    <div class="deal-row">
      @foreach($deals as $deal)
      <div class="deal-card">
        <div class="off">{{ $deal['off'] }}</div>
        <h4>{{ $deal['merchant'] }}</h4>
        <p>{{ $deal['description'] }}</p>
        <span class="deal-code">{{ $deal['code'] }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="wrap section">
  <div class="cta-band">
    <div>
      <h2>Are you a merchant or contractor in {{ $city ?? 'Guwahati' }}?</h2>
      <p>List your business free in 2 minutes and receive direct customer enquiries on your verified phone.</p>
    </div>
    <div class="actions">
      <a class="btn btn-outline" href="{{ route('business.create') }}">View merchant plans</a>
      <a class="btn btn-gold" href="{{ route('business.create') }}">Claim your free profile →</a>
    </div>
  </div>
</div>

@endsection
