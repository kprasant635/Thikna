@extends('layouts.app')

@section('title', 'Register — Thikana')
@section('minimal-footer', true)

@push('styles')
<style>
  .register-layout {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .register-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 36px 40px;
    width: 100%;
    max-width: 540px;
    box-shadow: 0 12px 36px rgba(15, 94, 46, 0.08);
  }
  .register-header {
    margin-bottom: 24px;
  }
  .register-header h1 {
    font-size: 28px;
    margin-bottom: 8px;
  }
  .register-header p {
    color: var(--ink-soft);
    font-size: 14px;
    line-height: 1.5;
  }
  .register-form .field {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .register-form label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
  }
  .register-form label .req {
    color: #e53e3e;
  }
  .register-form label .optional {
    color: var(--ink-soft);
    font-weight: 400;
    font-size: 12px;
  }
  .register-form input[type="text"],
  .register-form input[type="email"],
  .register-form input[type="tel"],
  .register-form textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: var(--ink);
    background: #fff;
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .register-form input:focus,
  .register-form textarea:focus {
    outline: none;
    border-color: var(--green-deep);
    box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
  }
  .otp-input-row {
    display: flex;
    gap: 10px;
    align-items: stretch;
  }
  .otp-btn {
    white-space: nowrap;
    padding: 10px 18px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .otp-message {
    font-size: 12.5px;
    color: var(--ink-soft);
    margin-top: 4px;
    line-height: 1.4;
  }
  .otp-message.success {
    color: var(--green-deep);
    font-weight: 600;
  }
  .otp-message.error {
    color: #e53e3e;
    font-weight: 600;
  }
  .verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--green-tint-2);
    color: var(--green-deep);
    font-weight: 700;
    font-size: 12.5px;
    padding: 6px 12px;
    border-radius: 999px;
    margin-top: 6px;
    border: 1px solid rgba(8, 143, 54, 0.2);
  }
  .verified-badge .change-phone {
    font-size: 11px;
    color: var(--ink-soft);
    text-decoration: underline;
    margin-left: 6px;
    cursor: pointer;
  }
  .submit-row {
    margin-top: 26px;
  }
  .submit-btn {
    width: 100%;
    padding: 13px 20px;
    font-size: 15px;
    border-radius: var(--radius-sm);
  }
  .terms-text {
    font-size: 12px;
    color: var(--ink-soft);
    text-align: center;
    margin-top: 12px;
    line-height: 1.4;
  }
  .field-error {
    color: #e53e3e;
    font-size: 12px;
    margin-top: 3px;
    font-weight: 500;
  }
  .alert-banner {
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    margin-bottom: 20px;
  }
  .alert-banner.error {
    background: #fff5f5;
    border: 1px solid #feb2b2;
    color: #c53030;
  }
  .alert-banner.success {
    background: var(--green-tint);
    border: 1px solid var(--green-tint-2);
    color: var(--green-deep);
  }
  .debug-otp-hint {
    background: #fffbe6;
    border: 1px dashed #d4b106;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    color: #725e00;
    margin-top: 6px;
  }
</style>
@endpush

@section('content')

<div class="wrap" style="padding-top:40px; padding-bottom:60px;">
  <div class="register-layout">
    <div class="register-card">
      <div class="register-header">
        <h1>Create your account</h1>
        <p>Join Thikana to list your business, save favorite properties, and connect with local services.</p>
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
          <label for="name-input">Full name <span class="req">*</span></label>
          <input type="text" id="name-input" name="name" value="{{ old('name') }}" placeholder="e.g. Priya Das" required autocomplete="name">
          @error('name')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Mobile Number with OTP --}}
        <div class="field otp-field-group">
          <label for="phone-input">Mobile number <span class="req">*</span></label>
          <div class="otp-input-row">
            <input type="tel" id="phone-input" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile number" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="handlePhoneInput(this)" required autocomplete="tel">
            <button type="button" id="send-otp-btn" class="btn btn-outline otp-btn" onclick="handleSendOtp()">Verify via OTP</button>
          </div>
          <div class="otp-message" id="otp-message">An OTP will be sent to this number for verification.</div>
          
          {{-- Hidden OTP input field --}}
          <div class="otp-input-row" id="otp-verify-section" style="display: none; margin-top: 10px;">
            <input type="text" id="otp-input" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);">
            <button type="button" id="verify-otp-btn" class="btn btn-primary otp-btn" onclick="handleVerifyOtp()">Confirm OTP</button>
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
          <label for="email-input">Email address <span class="optional">(optional)</span></label>
          <input type="email" id="email-input" name="email" value="{{ old('email') }}" placeholder="priya@example.com" autocomplete="email">
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Referral Code --}}
        <div class="field">
          <label for="referral-input">Referral code <span class="optional">(optional)</span></label>
          <input type="text" id="referral-input" name="referral_code" value="{{ old('referral_code') }}" placeholder="e.g. THK12345" style="text-transform: uppercase;">
          @error('referral_code')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Address --}}
        <div class="field">
          <label for="address-input">Complete address <span class="req">*</span></label>
          <textarea id="address-input" name="address" rows="3" placeholder="Flat No, Building Name, Street, Locality..." required>{{ old('address') }}</textarea>
          @error('address')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="submit-row">
          <button type="submit" id="submit-btn" class="btn btn-gold submit-btn">Complete Registration</button>
          <div class="terms-text">By registering, you agree to our Terms of Service and Privacy Policy.</div>
        </div>

      </form>
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

        // Show local debug OTP helper if present in development
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
        phoneInput.style.background = 'var(--green-tint)';

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
    phoneInput.style.background = '#fff';
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
