@extends('layouts.app')

@section('title', 'Send Enquiry — ' . $business['name'] . ' — Thikana')
@section('minimal-footer', true)

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="breadcrumb-bar">
  <div class="wrap">
    <a href="{{ route('home') }}">Home</a> /
    <a href="{{ route('shops.index') }}">{{ $business['category'] }}</a> /
    <a href="{{ route('business.show', $business['slug']) }}">{{ $business['name'] }}</a> /
    Send Enquiry
  </div>
</div>

<div class="wrap enq-wrap">

  {{-- ── Page heading ── --}}
  <div class="enq-page-head">
    <div class="eyebrow-pill">✉️ Direct business contact — no middlemen</div>
    <h1>Send an enquiry to <span class="enq-name-accent">{{ $business['name'] }}</span></h1>
    <p class="enq-sub">Your message goes directly to the verified owner. Typical response time:
      <strong>under 15 minutes</strong> during business hours.</p>
  </div>

  <div class="enq-layout">

    {{-- ══ LEFT — Enquiry Form ══ --}}
    <section class="enq-form-col">

      @if(session('status'))
        <div class="enq-success-banner" id="enq-success-banner">
          <div class="enq-success-icon">✅</div>
          <div>
            <strong>Enquiry sent successfully!</strong>
            <p>{{ session('status') }} You can also reach them directly via WhatsApp or phone below.</p>
          </div>
        </div>
      @endif

      <form class="enq-form" method="post"
            action="{{ route('business.quote', $business['slug']) }}"
            id="enq-form" novalidate>
        @csrf

        {{-- Type of enquiry --}}
        <div class="enq-section-label">Type of enquiry</div>
        <div class="enq-type-grid" role="group" aria-label="Enquiry type">
          @php
            $enquiryTypes = [
              ['value' => 'product',   'icon' => '📦', 'label' => 'Product / item'],
              ['value' => 'service',   'icon' => '🔧', 'label' => 'Service / repair'],
              ['value' => 'quote',     'icon' => '💬', 'label' => 'Price / quote'],
              ['value' => 'wholesale', 'icon' => '🏭', 'label' => 'Wholesale / B2B'],
            ];
          @endphp
          @foreach($enquiryTypes as $type)
          <label class="enq-type-card" for="type-{{ $type['value'] }}">
            <input type="radio" name="need" id="type-{{ $type['value'] }}"
                   value="{{ $type['value'] }}"
                   {{ old('need', request('type', 'product')) === $type['value'] ? 'checked' : '' }}>
            <span class="enq-type-icon">{{ $type['icon'] }}</span>
            <span class="enq-type-text">{{ $type['label'] }}</span>
          </label>
          @endforeach
        </div>
        @error('need')<span class="enq-error">{{ $message }}</span>@enderror

        {{-- Contact details --}}
        <div class="enq-section-label" style="margin-top:26px;">Your contact details</div>
        <div class="enq-field-row">
          <div class="enq-field">
            <label for="enq-name">Full name <span class="enq-req">*</span></label>
            <input type="text" id="enq-name" name="name"
                   placeholder="e.g. Rohan Sharma"
                   value="{{ old('name') }}"
                   autocomplete="name" required>
            @error('name')<span class="enq-error">{{ $message }}</span>@enderror
          </div>
          <div class="enq-field">
            <label for="enq-phone">Mobile number <span class="enq-req">*</span></label>
            <input type="tel" id="enq-phone" name="phone"
                   placeholder="+91 98XXX XXXXX"
                   value="{{ old('phone') }}"
                   autocomplete="tel" required>
            @error('phone')<span class="enq-error">{{ $message }}</span>@enderror
          </div>
        </div>

        {{-- Product / service --}}
        <div class="enq-field">
          <label for="enq-product">Product / service interested in <span class="enq-req">*</span></label>
          <select id="enq-product" name="product" required>
            <option value="" disabled {{ old('product') ? '' : 'selected' }}>— Select an option —</option>
            @foreach($business['quote_options'] ?? ['General inquiry','Product purchase','Repair / service','Bulk / wholesale order'] as $opt)
              <option value="{{ $opt }}" {{ old('product') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
            <option value="other" {{ old('product') === 'other' ? 'selected' : '' }}>Other (describe below)</option>
          </select>
          @error('product')<span class="enq-error">{{ $message }}</span>@enderror
        </div>

        {{-- Budget + Timeline --}}
        <div class="enq-field-row">
          <div class="enq-field">
            <label for="enq-budget">Your budget (optional)</label>
            <select id="enq-budget" name="budget">
              <option value="">Any / not sure</option>
              <option value="under-5k">Under ₹5,000</option>
              <option value="5k-20k">₹5,000 – ₹20,000</option>
              <option value="20k-1L">₹20,000 – ₹1,00,000</option>
              <option value="above-1L">Above ₹1,00,000</option>
            </select>
          </div>
          <div class="enq-field">
            <label for="enq-timeline">When do you need it?</label>
            <select id="enq-timeline" name="timeline">
              <option value="asap">As soon as possible</option>
              <option value="this-week">This week</option>
              <option value="this-month">This month</option>
              <option value="flexible">Flexible</option>
            </select>
          </div>
        </div>

        {{-- Message --}}
        <div class="enq-field">
          <label for="enq-notes">Describe your requirement <span class="enq-req">*</span></label>
          <textarea id="enq-notes" name="notes" rows="5"
                    placeholder="e.g. I need 3 iPhone 14 screens for a repair shop. Please share bulk pricing and delivery time to Dispur area."
                    required>{{ old('notes') }}</textarea>
          <div class="enq-char-hint">Be specific — detailed messages get faster responses</div>
          @error('notes')<span class="enq-error">{{ $message }}</span>@enderror
        </div>

        {{-- Preferred contact method --}}
        <div class="enq-section-label" style="margin-top:6px;">Preferred contact method</div>
        <div class="enq-contact-chips" role="group">
          @php $contactOptions = [['whatsapp','💬 WhatsApp'],['call','📞 Phone call'],['both','Both']]; @endphp
          @foreach($contactOptions as [$val, $lbl])
            <label class="enq-contact-chip" for="contact-{{ $val }}">
              <input type="radio" name="contact_pref" id="contact-{{ $val }}"
                     value="{{ $val }}" {{ $val === 'whatsapp' ? 'checked' : '' }}>
              {{ $lbl }}
            </label>
          @endforeach
        </div>

        {{-- Submit --}}
        <div class="enq-submit-row">
          <button type="submit" class="btn btn-gold enq-submit-btn" id="enq-submit-btn">
            ✉️ Send enquiry to {{ $business['name'] }}
          </button>
          <p class="enq-privacy">🔒 Your number is shared only with this verified business. Not sold to third parties.</p>
        </div>
      </form>

      {{-- Quick-contact alternatives --}}
      <div class="enq-alt-contact">
        <div class="enq-alt-label">Or contact directly</div>
        <div class="enq-alt-row">
          <a class="enq-alt-btn enq-alt-call" href="tel:{{ $business['phone'] }}">
            📞 Call {{ $business['phone_display'] }}
          </a>
          <a class="enq-alt-btn enq-alt-wa" href="{{ $business['whatsapp_url'] }}" target="_blank" rel="noopener">
            💬 WhatsApp chat
          </a>
          <a class="enq-alt-btn enq-alt-dir" href="{{ $business['directions_url'] }}" target="_blank" rel="noopener">
            🗺️ Get directions
          </a>
        </div>
      </div>

    </section>

    {{-- ══ RIGHT — Sidebar ══ --}}
    <aside class="enq-sidebar">

      {{-- Business card --}}
      <div class="enq-biz-card">
        <div class="enq-biz-thumb">
          <span class="enq-biz-official">{{ $business['photo_badge'] }}</span>
          {{ $business['icon'] }}
        </div>
        <div class="enq-biz-info">
          <h2>{{ $business['name'] }}</h2>
          <p class="enq-biz-tagline">{{ $business['tagline'] }}</p>
          <div class="enq-biz-badges">
            @foreach($business['badges'] as $badge)
              <span class="badge {{ ($badge['gold'] ?? false) ? 'gold' : '' }}">{{ $badge['label'] }}</span>
            @endforeach
          </div>
          <div class="enq-biz-stats">
            @foreach($business['stats'] as $stat)
              <div class="enq-stat"><b>{{ $stat['value'] }}</b>{{ $stat['label'] }}</div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Hours --}}
      <div class="side-card">
        <h4>🕐 Hours of operation</h4>
        @foreach($business['hours'] as $row)
          <div class="hours-row {{ ($row['today'] ?? false) ? 'today' : '' }}">
            <span>{{ $row['label'] }}</span>
            <span>{{ $row['value'] }}</span>
          </div>
        @endforeach
      </div>

      {{-- Trust signals --}}
      <div class="side-card enq-trust-card">
        <h4>🛡️ Why enquire through Thikana?</h4>
        <ul class="enq-trust-list">
          <li><span class="enq-trust-icon">✅</span>Direct to verified business owner</li>
          <li><span class="enq-trust-icon">🚫</span>No brokers, no commission charges</li>
          <li><span class="enq-trust-icon">⚡</span>Average response under 15 minutes</li>
          <li><span class="enq-trust-icon">🔒</span>Your data stays private</li>
          <li><span class="enq-trust-icon">⭐</span>Rated {{ $business['rating'] }} with {{ number_format($business['review_count']) }} verified reviews</li>
        </ul>
      </div>

      {{-- Address / Map --}}
      <div class="side-card enq-map-card">
        <h4>📍 Location</h4>
        <div class="enq-map-placeholder">🗺️</div>
        <div class="enq-address">{{ $business['address'] }}</div>
        <a class="btn btn-outline enq-dir-btn" href="{{ $business['directions_url'] }}"
           target="_blank" rel="noopener">
          Open in Google Maps →
        </a>
      </div>

    </aside>
  </div>
</div>

@push('scripts')
<script>
(function(){
  // Highlight enquiry type card on selection
  var typeCards = document.querySelectorAll('.enq-type-card');
  typeCards.forEach(function(label){
    var radio = label.querySelector('input[type="radio"]');
    if(!radio) return;
    function sync(){ typeCards.forEach(function(c){ c.classList.remove('selected'); }); if(radio.checked) label.classList.add('selected'); }
    radio.addEventListener('change', sync);
    sync();
  });

  // Highlight contact preference chip
  var chips = document.querySelectorAll('.enq-contact-chip');
  chips.forEach(function(label){
    var radio = label.querySelector('input[type="radio"]');
    if(!radio) return;
    function sync(){ chips.forEach(function(c){ c.classList.remove('selected'); }); if(radio.checked) label.classList.add('selected'); }
    radio.addEventListener('change', sync);
    sync();
  });

  // Submit loading state
  var form = document.getElementById('enq-form');
  var btn  = document.getElementById('enq-submit-btn');
  if(form && btn){
    form.addEventListener('submit', function(){
      btn.innerHTML = '⏳ Sending…';
      btn.disabled = true;
    });
  }
})();
</script>
@endpush

@endsection

