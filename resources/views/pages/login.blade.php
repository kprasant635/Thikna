@extends('layouts.app')

@section('title', 'Log In — SKOP-X')
@section('minimal-footer', true)

@push('styles')
<style>
  .login-page-wrap {
    background: #f8fafc;
    padding: 50px 16px 70px 16px;
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', 'Inter', sans-serif;
  }
  .login-layout {
    width: 100%;
    max-width: 460px;
  }
  .login-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 36px 40px;
    box-shadow: 0 10px 30px rgba(26, 58, 143, 0.08);
  }
  .login-header {
    margin-bottom: 24px;
    text-align: center;
  }
  .login-brand-badge {
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
  .login-header h1 {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
  }
  .login-header p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
  }
  .login-form .field {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .login-form label {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
  }
  .login-form input[type="tel"] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Inter', sans-serif;
    color: #0f172a;
    background: #f8fafc;
    transition: all .15s ease;
    letter-spacing: 0.5px;
  }
  .login-form input:focus {
    outline: none;
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
  }
  .alert-box {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13.5px;
    margin-bottom: 20px;
  }
  .alert-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
  }
  .alert-warning {
    background: #fffbe6;
    border: 1px solid #ffe58f;
    color: #8c6b00;
  }
  .btn-submit {
    width: 100%;
    padding: 14px 20px;
    background: linear-gradient(135deg, #1a3a8f, #2563eb);
    color: #ffffff;
    font-weight: 700;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    transition: all 0.2s ease;
    font-family: 'Poppins', sans-serif;
  }
  .btn-submit:hover {
    background: linear-gradient(135deg, #16327c, #1d4ed8);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
  }
  .register-redirect-text {
    font-size: 13.5px;
    color: #64748b;
    text-align: center;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
  }
  .register-redirect-text a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
  }
  .register-redirect-text a:hover {
    text-decoration: underline;
  }

  @media (max-width: 480px) {
    .login-card {
      padding: 24px 20px;
    }
  }
</style>
@endpush

@section('content')
<div class="login-page-wrap">
  <div class="login-layout">
    <div class="login-card">
      <div class="login-header">
        <div class="login-brand-badge">⚡ SKOP-X Platform</div>
        <h1>Welcome Back 👋</h1>
        <p>Enter your registered 10-digit mobile number to log in</p>
      </div>

      @if (session('warning'))
        <div class="alert-box alert-warning">
          {{ session('warning') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert-box alert-danger">
          <ul style="margin: 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="login-form" action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="field">
          <label for="phone">Mobile Number <span style="color:#ef4444;">*</span></label>
          <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile number" maxlength="10" pattern="[0-9]{10}" required autofocus />
        </div>

        <button type="submit" class="btn-submit">Log In →</button>
      </form>

      <div class="register-redirect-text">
        Don't have an account yet? <a href="{{ route('register') }}">Register here</a>
      </div>
    </div>
  </div>
</div>
@endsection
