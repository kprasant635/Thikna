@hasSection('minimal-footer')
<footer class="site-footer" style="background:var(--sx-navy,#0a1628);border-top:none;padding:0;margin-top:0;">
  <div class="wrap footer-bottom" style="color:rgba(255,255,255,0.5);padding:16px 0;">
    <div>&copy; {{ date('Y') }} SKOP-X. All Rights Reserved.</div>
  </div>
</footer>
@else
<footer class="site-footer" style="background:#0a1628;border-top:none;padding:0;margin-top:0;color:#fff;">
  <div class="wrap" style="padding-top:40px;padding-bottom:20px;">
    <div class="footer-grid" style="grid-template-columns:1.5fr 1fr 1fr 1fr;gap:30px;">
      {{-- Brand Column --}}
      <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
          <div style="background:linear-gradient(135deg,#1a3a8f,#2563eb);border-radius:8px;padding:5px 10px;font-size:12px;font-weight:800;color:#fff;font-family:'Poppins',sans-serif;">SKOP-X</div>
          <div style="font-family:'Poppins',sans-serif;font-size:18px;font-weight:800;color:#fff;">SKOP-X</div>
        </div>
        <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-bottom:12px;font-family:'Poppins',sans-serif;">Learn | Grow | Earn | Together</div>
        <p style="font-size:12px;color:rgba(255,255,255,0.5);max-width:280px;line-height:1.6;font-family:'Poppins',sans-serif;">
          SKOP-X is a platform for learning, skill development and income opportunities. Together we build a better tomorrow.
        </p>
        <div style="display:flex;gap:10px;margin-top:16px;">
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;font-size:14px;" title="WhatsApp">💬</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);display:flex;align-items:center;justify-content:center;font-size:14px;" title="Instagram">📷</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;font-weight:800;" title="X">𝕏</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#FF0000;display:flex;align-items:center;justify-content:center;font-size:14px;" title="YouTube">▶</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#0088cc;display:flex;align-items:center;justify-content:center;font-size:14px;" title="Telegram">✈️</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#1877F2;display:flex;align-items:center;justify-content:center;font-size:14px;" title="Facebook">📘</a>
          <a href="#" style="width:32px;height:32px;border-radius:50%;background:#0A66C2;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;" title="LinkedIn">in</a>
        </div>
      </div>

      {{-- Quick Links --}}
      <div>
        <h4 style="color:#fff;font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;margin-bottom:16px;">Quick Links</h4>
        <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">Home</a>
        <a href="#" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">SKOP-X Course</a>
        <a href="#" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">Achievers</a>
        <a href="{{ route('register') }}" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">Join Now</a>
        <a href="#" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">Contact Us</a>
      </div>

      {{-- Support --}}
      <div>
        <h4 style="color:#fff;font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;margin-bottom:16px;">Support</h4>
        <a href="mailto:support@skop-x.in" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">📧 support@skop-x.in</a>
        <a href="tel:+919876543210" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">📞 +91 98765 43210</a>
        <a href="#" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">💬 WhatsApp</a>
        <a href="#" style="color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">❓ Help Center</a>
      </div>

      {{-- Community --}}
      <div style="text-align:center;">
        <h4 style="color:#fff;font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;margin-bottom:16px;">Join Our Community</h4>
        <div style="width:100px;height:100px;background:#fff;border-radius:8px;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#333;padding:8px;text-align:center;font-weight:600;">
          QR Code
        </div>
        <div style="font-size:12px;color:rgba(255,255,255,0.6);font-family:'Poppins',sans-serif;">Scan & Connect</div>
      </div>
    </div>

    {{-- Bottom bar --}}
    <div class="footer-bottom" style="border-top-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.4);margin-top:30px;padding-top:16px;">
      <div style="font-family:'Poppins',sans-serif;font-size:12px;">&copy; {{ date('Y') }} SKOP-X. All Rights Reserved.</div>
      <div style="font-family:'Poppins',sans-serif;font-size:12px;">Skill India | Digital India | Atmanirbhar Bharat | Better Tomorrow</div>
    </div>
  </div>
</footer>
@endif
