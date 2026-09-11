@extends('layouts.app')

@section('title', 'Login — Thikana')
@section('minimal-footer', true)

@push('styles')
<style>
  .login-layout {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 16px;
  }
  .login-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 36px 40px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 12px 36px rgba(15, 94, 46, 0.08);
  }
  .login-header {
    margin-bottom: 24px;
    text-align: center;
  }
  .login-header h1 {
    font-size: 26px;
    margin-bottom: 8px;
    color: var(--green-deep);
  }
  .login-header p {
    color: var(--ink-soft);
    font-size: 14px;
  }
  .login-form .field {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .login-form label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
  }
  .login-form input[type="tel"] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-sm);
    font-size: 15px;
    letter-spacing: 0.5px;
  }
  .login-form input:focus {
    outline: none;
    border-color: var(--green-deep);
    box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.12);
  }
  .alert-box {
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 13.5px;
    margin-bottom: 20px;
  }
  .alert-danger {
    background: #fff5f5;
    border: 1px solid #feb2b2;
    color: #c53030;
  }
  .alert-warning {
    background: #fffaf0;
    border: 1px solid #fbd38d;
    color: #c05621;
  }
  .btn-submit {
    width: 100%;
    padding: 12px;
    background: var(--green-deep);
    color: white;
    font-weight: 600;
    border-radius: var(--radius-sm);
    border: none;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.2s ease;
  }
  .btn-submit:hover {
    background: #0b4822;
  }
</style>
@endpush

@section('content')
<div class="login-layout">
  <div class="login-card">
    <div class="login-header">
      <h1>Welcome Back to Thikana 👋</h1>
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
        <label for="phone">Mobile Number</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="10-digit mobile number" maxlength="10" pattern="[0-9]{10}" required autofocus />
      </div>

      <button type="submit" class="btn-submit">Log In</button>
    </form>

    <div style="margin-top: 24px; text-align: center; font-size: 14px; color: var(--ink-soft);">
      Don't have an account yet? <a href="{{ route('register') }}" style="color: var(--green-deep); font-weight: 600;">Register here</a>
    </div>
  </div>
</div>
@endsection
