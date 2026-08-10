<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Harita Music Academy Admin Panel</title>
  <link rel="stylesheet" href="{{ asset('admin-assets/css/') }}/style.css">
  <style>
    body {
      background-color: #f8fafc;
      background-image: radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.03) 0, transparent 50%),
        radial-gradient(at 50% 0%, rgba(20, 85, 61, 0.05) 0, transparent 50%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      font-family: var(--font-main);
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      animation: slideUp 0.6s var(--transition-cubic) forwards;
    }

    .login-card {
      background-color: var(--bg-card);
      border-radius: var(--radius-lg);
      border: 1px solid #e2e8f0;
      box-shadow: var(--shadow-lg);
      padding: 2.25rem 2.5rem;
      position: relative;
    }

    .login-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: var(--primary);
      border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .login-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .login-logo img,
    .login-logo svg {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 2.5px solid var(--primary);
      background-color: #ffffff;
      padding: 3px;
      box-shadow: var(--shadow-sm);
      object-fit: cover;
      margin-bottom: 0.75rem;
    }

    .login-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-align: center;
      color: var(--primary-dark);
      margin-bottom: 0.25rem;
    }

    .login-subtitle {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .role-select-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.5rem;
      margin-bottom: 1.25rem;
    }

    .role-btn {
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
      padding: 0.6rem 0.25rem;
      border-radius: 10px;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      text-transform: uppercase;
      transition: all 0.2s;
      text-align: center;
      box-shadow: none;
    }

    .role-btn:hover {
      background: #e2e8f0;
      color: var(--text-main);
    }

    .role-btn.selected {
      background-color: var(--primary);
      border-color: var(--primary);
      color: var(--text-white);
      box-shadow: var(--shadow-sm);
    }

    .login-footer {
      margin-top: 1.5rem;
      color: var(--text-muted);
      font-size: 11px;
      font-weight: 500;
      text-align: center;
      line-height: 1.45;
    }

    .login-footer a {
      color: var(--primary);
      font-weight: 600;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .login-card {
        padding: 1.75rem 1.25rem !important;
      }
      .role-select-grid {
        gap: 0.35rem !important;
      }
      .role-btn {
        font-size: 10px !important;
        padding: 0.5rem 0.15rem !important;
      }
    }
  </style>
</head>

<body>

  <!-- PRELOADER -->
  <div id="preloader" class="preloader-overlay">
    <div class="preloader-content">
      <img src="{{ asset('admin-assets/assets/') }}/logo.png" class="preloader-logo" alt="Harita Logo">
      <div class="preloader-spinner"></div>
    </div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <img src="{{ asset('admin-assets/assets/') }}/logo.png" width="80" height="80" alt="Harita Logo" style="object-fit: contain;">
        <h1 class="login-title">Harita Music Academy</h1>
        <p class="login-subtitle">Academy Portal & Administrative Panel</p>
      </div>

      <form id="loginForm" method="POST" action="{{ route('login.store') }}">
        @csrf

        @if ($errors->any())
          <div class="alert alert-danger" style="padding: 0.65rem 0.9rem; border-radius: 8px; background: #fee2e2; color: #b91c1c; font-size: 12.5px; margin-bottom: 1rem;">
            {{ $errors->first() }}
          </div>
        @endif

        @if (session('success'))
          <div style="padding: 0.65rem 0.9rem; border-radius: 8px; background: #d1fae5; color: #065f46; font-size: 12.5px; margin-bottom: 1rem;">
            {{ session('success') }}
          </div>
        @endif

        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="admin@haritamusic.com" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
        </div>

        <div class="d-flex align-center justify-between mb-3">
          <label class="checkbox-label" style="font-size:11.5px;">
            <input type="checkbox" name="remember"> Remember me
          </label>
          <a href="{{ route('password.request') }}" class="btn-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Sign In</button>
      </form>
    </div>

    <!-- Developed by Sitesoch footer -->
    <div class="login-footer">
      <p>© 2026 Harita Music Academy. All rights reserved.| Developed by <a href="https://sitesoch.com"
          target="_blank">Sitesoch</a></p>
    </div>
  </div>

  <script>
    // Hide preloader once page loads
    document.addEventListener('DOMContentLoaded', () => {
      const pre = document.getElementById('preloader');
      if (pre) setTimeout(() => { pre.style.opacity = 0; setTimeout(() => pre.remove(), 400); }, 300);
    });
  </script>
</body>

</html>