<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login — SKOP-X</title>
    <link rel="icon" type="image/png" href="{{ asset('images/skopx-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-bg: #0b1329;
            --admin-card: #131d3b;
            --admin-card-border: #1e2d59;
            --admin-primary: #3b82f6;
            --admin-primary-hover: #2563eb;
            --admin-accent: #06b6d4;
            --admin-text: #f8fafc;
            --admin-muted: #94a3b8;
            --admin-input-bg: #0c142b;
            --admin-input-border: #243566;
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.12) 0px, transparent 50%);
        }

        .admin-login-card {
            width: 100%;
            max-width: 440px;
            background: var(--admin-card);
            border: 1px solid var(--admin-card-border);
            border-radius: 18px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
        }

        .admin-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .admin-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .admin-title {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .admin-subtitle {
            font-size: 13.5px;
            color: var(--admin-muted);
            line-height: 1.5;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            background: var(--admin-input-bg);
            border: 1.5px solid var(--admin-input-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14px;
            color: #ffffff;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            cursor: pointer;
            user-select: none;
        }

        .form-check input {
            accent-color: var(--admin-primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .form-check label {
            font-size: 13px;
            color: var(--admin-muted);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 600;
            padding: 13px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.5);
        }

        .admin-footer-links {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--admin-card-border);
            text-align: center;
            font-size: 12.5px;
            color: var(--admin-muted);
        }

        .admin-footer-links a {
            color: #60a5fa;
            text-decoration: none;
        }

        .admin-footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="admin-login-card">
        <div class="admin-brand">
            <img src="{{ asset('images/skopx-logo.png') }}" alt="SKOP-X Logo" class="admin-logo">
            <br>
            <div class="admin-badge">
                <span>🛡️ System Management</span>
            </div>
            <h1 class="admin-title">Admin Portal</h1>
            <p class="admin-subtitle">Sign in with your username and password to manage SKOP-X</p>
        </div>

        {{-- Flash Alerts --}}
        @if (session('error'))
            <div class="alert alert-danger">
                <span>⚠️</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <span>✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-danger" style="background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.3); color: #93c5fd;">
                <span>ℹ️</span>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="{{ old('username') }}" placeholder="Enter admin username (e.g. admin)" required autofocus>
                @error('username')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Enter password" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Keep me logged in on this browser</label>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign In to Admin Dashboard</span>
                <span>→</span>
            </button>
        </form>

        <div class="admin-footer-links">
            <a href="{{ route('home') }}">← Return to SKOP-X Home</a>
        </div>
    </div>

</body>
</html>
