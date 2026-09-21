@extends('layouts.app')

@section('title', 'Create Account — SKOP-X')
@section('minimal-footer', true)

@push('styles')
<style>
  .register-page-wrap {
    background: #f8fafc;
    padding: 40px 16px 60px 16px;
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .register-layout {
    width: 100%;
    max-width: 540px;
  }
  .register-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 36px 40px;
    box-shadow: 0 10px 30px rgba(26, 58, 143, 0.08);
  }
  .register-header {
    margin-bottom: 24px;
    text-align: center;
  }
  .register-brand-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, rgba(26, 58, 143, 0.08), rgba(37, 99, 235, 0.12));
    color: #1a3a8f;
    font-size: 12.5px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .register-header h1 {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
  }
  .register-header p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
  }

  /* Step indicator */
  .sx-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    margin-bottom: 28px;
    padding: 0 10px;
  }
  .sx-step-line {
    position: absolute;
    top: 15px;
    left: 40px;
    right: 40px;
    height: 2px;
    background: #e2e8f0;
    z-index: 1;
  }
  .sx-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }
  .sx-step-num {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #cbd5e1;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
  }
  .sx-step.active .sx-step-num {
    background: linear-gradient(135deg, #1a3a8f, #2563eb);
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
  }
  .sx-step.completed .sx-step-num {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  .sx-step-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
  }
  .sx-step.active .sx-step-label {
    color: #1a3a8f;
    font-weight: 700;
  }

  /* Form Styling */
  .register-form .field {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .register-form label {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
  }
  .register-form label .req {
    color: #ef4444;
  }
  .register-form label .optional {
    color: #94a3b8;
    font-weight: 400;
    font-size: 12px;
  }
  .register-form input[type="text"],
  .register-form input[type="email"],
  .register-form input[type="tel"],
  .register-form textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #0f172a;
    background: #f8fafc;
    transition: all .15s ease;
  }
  .register-form input:focus,
  .register-form textarea:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
  }
  .otp-input-row {
    display: flex;
    gap: 10px;
    align-items: stretch;
  }
  .otp-btn {
    white-space: nowrap;
    padding: 11px 18px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a3a8f, #2563eb);
    color: #ffffff;
    font-weight: 600;
    font-size: 13.5px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .otp-btn:hover {
    background: linear-gradient(135deg, #16327c, #1d4ed8);
    transform: translateY(-1px);
  }
  .otp-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
  }
  .otp-message {
    font-size: 12.5px;
    color: #64748b;
    margin-top: 4px;
    line-height: 1.4;
  }
  .otp-message.success {
    color: #16a34a;
    font-weight: 600;
  }
  .otp-message.error {
    color: #dc2626;
    font-weight: 600;
  }
  .verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #dcfce7;
    color: #15803d;
    font-weight: 700;
    font-size: 12.5px;
    padding: 6px 14px;
    border-radius: 999px;
    margin-top: 6px;
    border: 1px solid #bbf7d0;
  }
  .verified-badge .change-phone {
    font-size: 11px;
    color: #166534;
    text-decoration: underline;
    margin-left: 6px;
    cursor: pointer;
  }
  .submit-row {
    margin-top: 26px;
  }
  .submit-btn {
    width: 100%;
    padding: 14px 20px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 8px;
    background: linear-gradient(135deg, #ff9900, #f97316);
    color: #ffffff;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
    transition: all 0.2s ease;
    font-family: 'Poppins', sans-serif;
  }
  .submit-btn:hover {
    background: linear-gradient(135deg, #e68a00, #ea580c);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(249, 115, 22, 0.4);
  }
  .terms-text {
    font-size: 12px;
    color: #64748b;
    text-align: center;
    margin-top: 14px;
    line-height: 1.4;
  }
  .login-redirect-text {
    font-size: 13.5px;
    color: #64748b;
    text-align: center;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
  }
  .login-redirect-text a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
  }
  .login-redirect-text a:hover {
    text-decoration: underline;
  }
  .field-error {
    color: #dc2626;
    font-size: 12px;
    margin-top: 3px;
    font-weight: 500;
  }
  .alert-banner {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 20px;
  }
  .alert-banner.error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
  }
  .alert-banner.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
  }
  .debug-otp-hint {
    background: #fffbe6;
    border: 1px dashed #d4b106;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: #725e00;
    margin-top: 8px;
  }

  @media (max-width: 580px) {
    .register-card {
      padding: 24px 20px;
    }
  }
</style>
@endpush

@section('content')

<div class="register-page-wrap">
  <div class="register-layout">
    <div class="register-card">
      <div class="register-header">
        <div class="register-brand-badge">⚡ SKOP-X Platform</div>
        <h1>Create Your Account</h1>
        <p>Start your journey with SKOP-X to access video courses, practical training, and real opportunities.</p>
      </div>

      {{-- Step Indicator --}}
      <div class="sx-steps">
        <div class="sx-step-line"></div>
        <div class="sx-step active">
          <div class="sx-step-num">1</div>
          <div class="sx-step-label">Registration</div>
        </div>
        <div class="sx-step">
          <div class="sx-step-num">2</div>
          <div class="sx-step-label">Verification</div>
        </div>
        <div class="sx-step">
          <div class="sx-step-num">3</div>
          <div class="sx-step-label">Complete</div>
        </div>
      </div>

      {{-- General Error Banner --}}
      @if ($errors->any())
        <div class="alert-banner error">
          <strong>Please resolve the following errors:</strong>
          <ul style="margin: 6px 0 0 18px; padding: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert-banner success">
          {{ session('success') }}
        </div>
      @endif

      <form id="register-form" class="register-form" method="post" action="{{ route('register.submit') }}" onsubmit="return handleFormSubmit(event)">
        @csrf
        
        {{-- Full Name --}}
        <div class="field">
          <label for="name-input">Full Consumer Name <span class="req">*</span></label>
          <input type="text" id="name-input" name="name" value="{{ old('name') }}" placeholder="e.g. Priya Das" required autocomplete="name">
          @error('name')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Mobile Number with OTP --}}
        <div class="field otp-field-group">
          <label for="phone-input">Mobile Number <span class="req">*</span></label>
          <div class="otp-input-row">
            <input type="tel" id="phone-input" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile number" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="handlePhoneInput(this)" required autocomplete="tel">
            <button type="button" id="send-otp-btn" class="otp-btn" onclick="handleSendOtp()">Verify via OTP</button>
          </div>
          <div class="otp-message" id="otp-message">An OTP will be sent to this number for verification.</div>
          
          {{-- Hidden OTP input field --}}
          <div class="otp-input-row" id="otp-verify-section" style="display: none; margin-top: 10px;">
            <input type="text" id="otp-input" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);">
            <button type="button" id="verify-otp-btn" class="otp-btn" style="background:#16a34a;" onclick="handleVerifyOtp()">Confirm OTP</button>
          </div>

          {{-- Verified indicator --}}
          <div id="phone-verified-indicator" style="display: none;">
            <span class="verified-badge">
              <span>✓ Mobile Verified</span>
              <span class="change-phone" onclick="enablePhoneEditing()">Change</span>
            </span>
          </div>

          @error('phone')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="field">
          <label for="email-input">Email Address <span class="optional">(optional)</span></label>
          <input type="email" id="email-input" name="email" value="{{ old('email') }}" placeholder="priya@example.com" autocomplete="email">
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Referral Code --}}
        <div class="field">
          <label for="referral-input">Referral Code <span class="optional">(optional)</span></label>
          <input type="text" id="referral-input" name="referral_code" value="{{ old('referral_code', request('ref')) }}" placeholder="e.g. SKP12345" style="text-transform: uppercase;">
          @error('referral_code')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Address --}}
        <div class="field">
          <label for="address-input">Complete Address <span class="req">*</span></label>
          <textarea id="address-input" name="address" rows="3" placeholder="Flat No, Building Name, Street, Locality..." required>{{ old('address') }}</textarea>
          @error('address')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="submit-row">
          <button type="submit" id="submit-btn" class="submit-btn">Complete Registration →</button>
          <div class="terms-text">By registering, you agree to SKOP-X Terms of Service and Privacy Policy.</div>
        </div>

      </form>

      <div class="login-redirect-text">
        Already have an account? <a href="{{ route('login') }}">Log In here</a>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let isVerified = false;
  let countdownTimer = null;
  const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

  function handlePhoneInput(el) {
    el.value = el.value.replace(/[^0-9]/g, '').slice(0, 10);
    if (isVerified) {
      resetVerification();
    }
  }

  function setOtpMessage(text, type = 'info') {
    const msgEl = document.getElementById('otp-message');
    msgEl.innerText = text;
    msgEl.className = 'otp-message ' + type;
  }

  function startCooldown(seconds) {
    const btn = document.getElementById('send-otp-btn');
    btn.disabled = true;
    let remaining = seconds;
    btn.innerText = `Resend in ${remaining}s`;

    if (countdownTimer) clearInterval(countdownTimer);

    countdownTimer = setInterval(() => {
      remaining -= 1;
      if (remaining <= 0) {
        clearInterval(countdownTimer);
        btn.disabled = false;
        btn.innerText = 'Resend OTP';
      } else {
        btn.innerText = `Resend in ${remaining}s`;
      }
    }, 1000);
  }

  async function handleSendOtp() {
    const phoneInput = document.getElementById('phone-input');
    const sendBtn = document.getElementById('send-otp-btn');
    const phone = phoneInput.value.trim();

    if (!phone) {
      setOtpMessage('Please enter your mobile number.', 'error');
      phoneInput.focus();
      return;
    }

    if (!/^[0-9]{10}$/.test(phone)) {
      setOtpMessage('Please enter a valid 10-digit mobile number.', 'error');
      phoneInput.focus();
      return;
    }

    setOtpMessage('Sending verification code...', 'info');
    sendBtn.disabled = true;
    sendBtn.innerText = 'Sending...';

    try {
      const response = await fetch('{{ route('register.otp.send') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ phone: phone })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        setOtpMessage(data.message || 'OTP sent successfully. Enter the 6-digit code below.', 'success');
        document.getElementById('otp-verify-section').style.display = 'flex';
        document.getElementById('otp-input').focus();
        startCooldown(data.resend_after || 60);

        if (data.debug_otp) {
          const existingHint = document.getElementById('debug-otp-box');
          if (existingHint) existingHint.remove();
          const hint = document.createElement('div');
          hint.id = 'debug-otp-box';
          hint.className = 'debug-otp-hint';
          hint.innerHTML = '<strong>Dev SMS:</strong> Your OTP code is <code>' + data.debug_otp + '</code>';
          document.getElementById('otp-verify-section').after(hint);
        }
      } else {
        const errorMsg = data.message || (data.errors && Object.values(data.errors)[0][0]) || 'Failed to send OTP.';
        setOtpMessage(errorMsg, 'error');
        sendBtn.disabled = false;
        sendBtn.innerText = 'Verify via OTP';
      }
    } catch (err) {
      console.error(err);
      setOtpMessage('Network error occurred while sending OTP. Please check your connection.', 'error');
      sendBtn.disabled = false;
      sendBtn.innerText = 'Verify via OTP';
    }
  }

  async function handleVerifyOtp() {
    const phoneInput = document.getElementById('phone-input');
    const otpInput = document.getElementById('otp-input');
    const verifyBtn = document.getElementById('verify-otp-btn');
    const phone = phoneInput.value.trim();
    const otp = otpInput.value.trim();

    if (!otp || otp.length !== 6) {
      setOtpMessage('Please enter the complete 6-digit OTP.', 'error');
      otpInput.focus();
      return;
    }

    verifyBtn.disabled = true;
    verifyBtn.innerText = 'Verifying...';

    try {
      const response = await fetch('{{ route('register.otp.verify') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ phone: phone, otp: otp })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        isVerified = true;
        setOtpMessage('Mobile number verified successfully!', 'success');
        document.getElementById('otp-verify-section').style.display = 'none';
        document.getElementById('send-otp-btn').style.display = 'none';
        document.getElementById('phone-verified-indicator').style.display = 'block';
        phoneInput.readOnly = true;
        phoneInput.style.background = '#e0f2fe';

        const debugBox = document.getElementById('debug-otp-box');
        if (debugBox) debugBox.remove();
      } else {
        const errorMsg = data.message || (data.errors && Object.values(data.errors)[0][0]) || 'Verification failed.';
        setOtpMessage(errorMsg, 'error');
        verifyBtn.disabled = false;
        verifyBtn.innerText = 'Confirm OTP';
      }
    } catch (err) {
      console.error(err);
      setOtpMessage('Network error occurred during verification. Please try again.', 'error');
      verifyBtn.disabled = false;
      verifyBtn.innerText = 'Confirm OTP';
    }
  }

  function enablePhoneEditing() {
    resetVerification();
    const phoneInput = document.getElementById('phone-input');
    phoneInput.readOnly = false;
    phoneInput.style.background = '#f8fafc';
    phoneInput.focus();
  }

  function resetVerification() {
    isVerified = false;
    document.getElementById('phone-verified-indicator').style.display = 'none';
    document.getElementById('send-otp-btn').style.display = 'inline-flex';
    document.getElementById('send-otp-btn').disabled = false;
    document.getElementById('send-otp-btn').innerText = 'Verify via OTP';
    document.getElementById('otp-verify-section').style.display = 'none';
    setOtpMessage('An OTP will be sent to this number for verification.', 'info');
    if (countdownTimer) clearInterval(countdownTimer);
  }

  function handleFormSubmit(event) {
    if (!isVerified) {
      event.preventDefault();
      setOtpMessage('Please verify your mobile number via OTP before completing registration.', 'error');
      document.getElementById('phone-input').scrollIntoView({ behavior: 'smooth', block: 'center' });
      return false;
    }
    return true;
  }
</script>
@endpush

@endsection
