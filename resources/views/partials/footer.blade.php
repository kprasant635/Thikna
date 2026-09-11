@hasSection('minimal-footer')
<footer class="site-footer">
  <div class="wrap footer-bottom">
    <div>&copy; {{ date('Y') }} Thikana &middot; {{ $city ?? 'Guwahati' }} local marketplace &amp; directory</div>
    <div>{{ $city ?? 'Guwahati' }} &middot; Assam</div>
  </div>
</footer>
@else
<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <div class="brand-name" style="margin-bottom:10px;">Thikana</div>
        <p style="font-size:12.5px;color:var(--ink-soft);max-width:260px;">
          {{ $city ?? 'Guwahati' }} and Kamrup's local discovery network — verified shops, owners and sellers with direct-connect tools.
        </p>
      </div>
      <div>
        <h4>Popular areas</h4>
        @foreach(($popularAreas ?? ['Zoo Road','Ganeshguri','Beltola','Fancy Bazar']) as $area)
          <a href="{{ route('category.show', \Illuminate\Support\Str::slug($area)) }}">{{ $area }}</a>
        @endforeach
      </div>
      <div>
        <h4>Business solutions</h4>
        <a href="{{ route('business.create') }}">Claim local listing</a>
        <a href="#">Sponsored placement</a>
        <a href="#">Merchant badging</a>
      </div>
      <div>
        <h4>Directory help</h4>
        <a href="#">Customer support</a>
        <a href="#">Fraud &amp; scam prevention</a>
        <a href="#">Privacy notice</a>
      </div>
    </div>
    <div class="footer-bottom">
      <div>&copy; {{ date('Y') }} Thikana &middot; {{ $city ?? 'Guwahati' }} local marketplace &amp; directory</div>
      <div>{{ $city ?? 'Guwahati' }} &middot; Assam</div>
    </div>
  </div>
</footer>
@endif
