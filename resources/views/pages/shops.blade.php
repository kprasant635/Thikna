@extends('layouts.app')

@section('title', 'Shop Directory — Thikana')
@section('minimal-footer', true)

@section('content')

<div class="breadcrumb-bar">
  <div class="wrap">
    <a href="{{ route('home') }}">Home</a> /
    <a href="{{ route('home') }}">{{ $city ?? 'Guwahati' }}</a> /
    {{ $category ?? 'Electronics & Repairs' }} / {{ $subcategory ?? 'Mobile & Laptop Repair' }}
  </div>
</div>

<div class="wrap" style="padding-top:22px;">
  <div class="results-head">
    <div>
      <h1>{{ count($shops) }} verified shops found near
        <span style="color:var(--green-mid);">{{ $area ?? 'Downtown '.($city ?? 'Guwahati') }}</span></h1>
      <div class="results-count">{{ count($shops) }} active merchant nodes · avg lead response {{ $avgResponseMins ?? 4 }} mins</div>
    </div>
    <form method="get">
      <select name="sort" class="btn btn-outline" style="font-size:13px;" onchange="this.form.submit()">
        <option value="recommended" @selected(request('sort','recommended')=='recommended')>Sort: Recommended</option>
        <option value="rating" @selected(request('sort')=='rating')>Sort: Highest rated</option>
        <option value="distance" @selected(request('sort')=='distance')>Sort: Nearest</option>
      </select>
    </form>
  </div>

  <div class="active-filters">
    @foreach($activeFilters ?? ['Guwahati Downtown (5 km)','Verified merchant'] as $f)
      <span class="chip active">{{ $f }} ✕</span>
    @endforeach
  </div>
</div>

<div class="wrap section" style="padding-top:0;">
  <div class="results-layout">

    <aside class="filter-panel">
      <h4>Refine shops</h4>
      <div class="filter-group">
        <div class="label">STATUS</div>
        <div class="filter-option"><span><input type="checkbox" name="verified" checked> Verified only</span></div>
        <div class="filter-option"><span><input type="checkbox" name="open_now" checked> Open now</span></div>
        <div class="filter-option"><span><input type="checkbox" name="doorstep"> Doorstep repair</span></div>
      </div>
      <div class="filter-group">
        <div class="label">MINIMUM RATING</div>
        <div class="filter-option"><span><input type="radio" name="rating" value="4.5"> 4.5 &amp; up</span></div>
        <div class="filter-option"><span><input type="radio" name="rating" value="4.0"> 4.0 &amp; up</span></div>
      </div>
      <div class="filter-group">
        <div class="label">SEARCH RADIUS — {{ $radiusKm ?? 5 }} km</div>
        <input type="range" min="1" max="25" value="{{ $radiusKm ?? 5 }}" style="width:100%;accent-color:var(--green-bright);">
      </div>
      <div class="filter-group">
        <div class="label">ESTABLISHMENT TYPE</div>
        @foreach($establishmentTypes ?? [] as $type => $n)
          <div class="filter-option"><span>{{ $type }}</span><span class="n">{{ $n }}</span></div>
        @endforeach
      </div>
    </aside>

    <section>
      <div class="promo-strip">
        <div>
          <strong>Need a specific spare part or micro-soldering?</strong>
          <p>Post one request and get estimates from 5 local repair specialists within 10 minutes.</p>
        </div>
        <a class="btn btn-gold" href="{{ route('business.create') }}">Get quotes from 5 shops →</a>
      </div>

      @foreach($shops as $shop)
      <a class="merchant-card" href="{{ route('business.show', ['business' => $shop['slug']]) }}">
        <div class="merchant-thumb"><span class="tag">{{ $shop['status'] }}</span>
          @if(!empty($shop['image']))
            <img src="{{ asset($shop['image']) }}" alt="{{ $shop['name'] }}" class="thumb-img">
          @else
            {{ $shop['icon'] }}
          @endif
        </div>
        <div class="merchant-body">
          <h3>{{ $shop['name'] }} @if($shop['verified'])<span class="badge">✓ Verified</span>@endif</h3>
          <div class="merchant-meta">
            <span class="rating"><span class="star">★</span> {{ $shop['rating'] }}</span>
            <span>{{ $shop['reviews'] }} verified reviews</span>
            <span>· {{ $shop['distance'] }}</span>
          </div>
          <div class="merchant-desc">{{ $shop['description'] }}</div>
          <div class="merchant-tags">
            @foreach($shop['tags'] as $tag)<span>{{ $tag }}</span>@endforeach
          </div>
        </div>
        <div class="merchant-actions">
          <div class="btn btn-primary">{{ $shop['primary_action'] }}</div>
          <div class="btn btn-{{ $shop['secondary_style'] ?? 'gold' }}">{{ $shop['secondary_action'] }}</div>
        </div>
      </a>
      @endforeach

      @if(method_exists($shops, 'links'))
      <div class="pagination">
        {{ $shops->links() }}
      </div>
      @endif
    </section>

    <aside>
      <div class="map-box">🗺️ Map view — {{ count($shops) }} pins</div>
      <div class="side-card sidebar-live">
        <h4>Live activity in {{ $city ?? 'Guwahati' }}</h4>
        @foreach($liveActivity ?? [] as $item)
          <div class="live-item"><b>{{ $item['name'] }}</b> {{ $item['action'] }} · {{ $item['time'] }} ago</div>
        @endforeach
      </div>
      <div class="side-card">
        <h4>Discover by area</h4>
        @foreach($areaCounts ?? [] as $area => $count)
          <div class="hours-row"><span>{{ $area }}</span><span>{{ $count }} shops</span></div>
        @endforeach
      </div>
    </aside>

  </div>
</div>

@endsection
