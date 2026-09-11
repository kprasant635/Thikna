@extends('layouts.app')

@section('title', $business['name'].' — Thikana')
@section('minimal-footer', true)

@section('content')

<div class="breadcrumb-bar">
  <div class="wrap">
    <a href="{{ route('home') }}">Home</a> /
    <a href="{{ route('shops.index') }}">{{ $business['category'] }}</a> /
    {{ $business['subcategory'] }} / {{ $business['name'] }}
    @if($business['rank_badge']) <span class="badge" style="float:right;">{{ $business['rank_badge'] }}</span> @endif
  </div>
</div>

<div class="wrap">
  <div class="detail-header">
    <div class="detail-photo"><span class="official">{{ $business['photo_badge'] }}</span>{{ $business['icon'] }}</div>
    <div class="detail-info">
      <h1>{{ $business['name'] }}</h1>
      <div class="detail-sub">{{ $business['tagline'] }}</div>
      <div class="detail-badges">
        @foreach($business['badges'] as $badge)
          <span class="badge {{ $badge['gold'] ?? false ? 'gold' : '' }}">{{ $badge['label'] }}</span>
        @endforeach
      </div>
      <div class="detail-actions">
        <a class="btn btn-primary" href="tel:{{ $business['phone'] }}">Call {{ $business['phone_display'] }}</a>
        <a class="btn btn-gold" href="{{ $business['whatsapp_url'] }}">WhatsApp chat</a>
        <a class="btn btn-outline" href="{{ route('business.enquiry', $business['slug']) }}">Send enquiry</a>
        <a class="btn btn-outline" href="{{ $business['directions_url'] }}">Get directions</a>
      </div>
      <div class="stat-strip">
        @foreach($business['stats'] as $stat)
          <div class="s"><b>{{ $stat['value'] }}</b>{{ $stat['label'] }}</div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="tab-row">
    <a href="#" class="active">Products &amp; Inventory ({{ count($products) }})</a>
    <a href="#">Services offered</a>
    <a href="#">Photos &amp; videos ({{ $business['photo_count'] }})</a>
    <a href="#">Reviews &amp; ratings ({{ $business['review_count'] }})</a>
    <a href="#">About &amp; certifications</a>
  </div>

  <div class="detail-layout">
    <section>
      <div class="section-head" style="margin-bottom:14px;">
        <h2 style="font-size:20px;">Featured products &amp; spare parts catalog</h2>
      </div>
      <div class="product-grid">
        @foreach($products as $product)
        <div class="product-card">
          <div class="product-img">{{ $product['icon'] }}</div>
          <div class="product-body">
            <h4>{{ $product['name'] }}</h4>
            <div class="product-sku">SKU {{ $product['sku'] }} · {{ $product['stock_label'] }}</div>
            <div class="product-foot">
              <span class="price">₹{{ number_format($product['price']) }}</span>
              <a class="btn btn-tint" href="{{ route('business.enquiry', [$business['slug'], 'product' => $product['sku']]) }}">{{ $product['cta'] }}</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="map-box" style="margin-top:22px;">🗺️ {{ $business['address'] }}</div>

      <h2 style="font-size:20px;margin:26px 0 14px;">Recent customer reviews</h2>
      <div class="review-summary">
        <div class="score">{{ $business['rating'] }}</div>
        <div style="flex:1;">
          @foreach($ratingBreakdown as $stars => $pct)
            <div class="bar-row">{{ $stars }}★<div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;"></div></div>{{ $pct }}%</div>
          @endforeach
        </div>
      </div>
      @foreach($reviews as $review)
      <div class="review-item">
        <span class="who">{{ $review['name'] }} <span class="badge">{{ $review['badge'] }}</span></span>
        <span class="when">{{ $review['when'] }}</span>
        <p>{{ $review['text'] }}</p>
      </div>
      @endforeach
    </section>

    <aside>
      <form class="quote-box" method="post" action="{{ route('business.quote', $business['slug']) }}">
        @csrf
        <h4>⚡ Request instant quote</h4>
        <div class="field"><label>Full name</label><input type="text" name="name" placeholder="e.g. Jordan Miller"></div>
        <div class="field"><label>Mobile number</label><input type="text" name="phone" placeholder="+91 98XXX XXXXX"></div>
        <div class="field"><label>Service or product needed</label>
          <select name="need">
            @foreach($business['quote_options'] as $opt)<option>{{ $opt }}</option>@endforeach
          </select>
        </div>
        <div class="field"><label>Notes</label><textarea name="notes" rows="3" placeholder="e.g. iPhone 14 Pro, cracked front glass"></textarea></div>
        <button type="submit" class="btn btn-gold" style="display:block;width:100%;">Submit request</button>
      </form>
      <div class="side-card">
        <h4>Hours of operation</h4>
        @foreach($business['hours'] as $row)
          <div class="hours-row {{ $row['today'] ?? false ? 'today' : '' }}"><span>{{ $row['label'] }}</span><span>{{ $row['value'] }}</span></div>
        @endforeach
      </div>
      <div class="side-card">
        <h4>Accepted payment methods</h4>
        @foreach($business['payment_methods'] as $method => $value)
          <div class="hours-row"><span>{{ $method }}</span><span>{{ $value }}</span></div>
        @endforeach
      </div>
    </aside>
  </div>
</div>

@endsection
