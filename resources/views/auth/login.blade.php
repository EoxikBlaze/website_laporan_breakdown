<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | General Service PT. PAMA SITE . ARIA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/js/app.tsx'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f1f5f9;
        }

        /* Left Branding Panel */
        .brand-panel {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 45%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 60%, #06b6d4 100%);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .brand-logo-box {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .brand-logo-box svg { width: 40px; height: 40px; }
        .brand-title { font-size: 1.75rem; font-weight: 700; color: #fff; text-align: center; line-height: 1.3; }
        .brand-subtitle { font-size: 0.875rem; color: rgba(255,255,255,0.75); text-align: center; margin-top: 0.75rem; line-height: 1.6; }
        .brand-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            margin-top: 2rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 99px;
            color: #fff; font-size: 0.75rem; font-weight: 500;
        }
        .feature-list { list-style: none; margin-top: 2rem; width: 100%; max-width: 280px; }
        .feature-list li {
            display: flex; align-items: center; gap: 0.75rem;
            color: rgba(255,255,255,0.85); font-size: 0.875rem;
            padding: 0.5rem 0;
        }
        .feature-check {
            width: 20px; height: 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* Right Login Panel */
        .login-panel {
            flex: 1;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            padding: 2rem;
            background: #fff;
        }
        .login-card { width: 100%; max-width: 400px; }
        .login-header { margin-bottom: 2rem; }
        .login-header h1 { font-size: 1.75rem; font-weight: 700; color: #0f172a; }
        .login-header p { font-size: 0.875rem; color: #64748b; margin-top: 0.375rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem; color: #111827;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none; background: #fafafa;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            background: #fff;
        }
        .form-input.has-error { border-color: #f43f5e; background: #fff5f5; }
        .error-msg { font-size: 0.75rem; color: #f43f5e; margin-top: 0.375rem; }
        .btn-login {
            width: 100%; padding: 0.8rem;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: #fff; font-weight: 600; font-size: 0.9375rem;
            border: none; border-radius: 10px; cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
            box-shadow: 0 4px 14px rgba(59,130,246,0.35);
        }
        .btn-login:hover { transform: translateY(-1px); filter: brightness(1.08); }
        .btn-login:active { transform: translateY(0); }
        .footer-note { text-align: center; margin-top: 2rem; font-size: 0.75rem; color: #94a3b8; }

        @media (min-width: 768px) {
            .brand-panel { display: flex; }
        }
    </style>
</head>
<body>
    {{-- LEFT: Branding Panel --}}
    <div class="brand-panel">
        <div class="brand-logo-box">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="48" height="48" rx="12" fill="rgba(255,255,255,0.1)"/>
                <path d="M10 34L20 18L28 28L34 20L42 34H10Z" fill="white" opacity="0.9"/>
                <circle cx="34" cy="14" r="4" fill="rgba(255,255,255,0.75)"/>
            </svg>
        </div>
        <h1 class="brand-title">General Service<br>System</h1>
        <p class="brand-subtitle">PT. Pamapersada Nusantara<br>Site ARIA</p>
        <div class="brand-badge">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            General Service PAMA ARIA
        </div>
        <ul class="feature-list">
            <li>
                <span class="feature-check"><svg width="10" height="10" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                GAK TAU ISI APA HEHE
            </li>
            <li>
                <span class="feature-check"><svg width="10" height="10" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                GAK TAU ISI APA HEHE
            </li>
            <li>
                <span class="feature-check"><svg width="10" height="10" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                LAH
            </li>
        </ul>
    </div>

    {{-- RIGHT: Login Panel --}}
    <div class="login-panel">
        <div class="login-card">
            <div class="login-header">
                <h1>Selamat Datang</h1>
                <p>Masuk</p>
            </div>

            @if($errors->any())
            <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.8125rem;color:#dc2626;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input {{ $errors->has('email') ? 'has-error' : '' }}"
                        type="email" id="email" name="email"
                        value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-input {{ $errors->has('password') ? 'has-error' : '' }}"
                        type="password" id="password" name="password"
                        placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">P LOGIN</button>
            </form>

            <p class="footer-note">
                &copy; {{ date('Y') }} PT. Pamapersada Nusantara &mdash; GS ARIA
            </p>
        </div>
    </div>
</body>
</html>
