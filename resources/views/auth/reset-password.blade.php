<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Harita Music Academy</title>
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

    .reset-container {
      width: 100%;
      max-width: 420px;
      animation: slideUp 0.6s var(--transition-cubic) forwards;
    }

    .reset-card {
      background-color: var(--bg-card);
      border-radius: var(--radius-lg);
      border: 1px solid #e2e8f0;
      box-shadow: var(--shadow-lg);
      padding: 2.25rem 2.5rem;
      position: relative;
    }

    .reset-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: var(--primary);
      border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .reset-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .reset-logo svg,
    .reset-logo img {
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

    .reset-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-align: center;
      color: var(--primary-dark);
      margin-bottom: 0.35rem;
    }

    .reset-subtitle {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-align: center;
      margin-bottom: 1.5rem;
      line-height: 1.45;
      font-weight: 500;
    }

    .reset-footer {
      margin-top: 1.5rem;
      color: var(--text-muted);
      font-size: 11px;
      font-weight: 500;
      text-align: center;
      line-height: 1.45;
    }

    .reset-footer a {
      color: var(--primary);
      font-weight: 600;
    }

    .reset-footer a:hover {
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .reset-card {
        padding: 1.75rem 1.25rem !important;
      }
    }
  </style>
</head>

<body>

  <div class="reset-container">
    <div class="reset-card">
      <div class="reset-logo">
        <img src="{{ asset('admin-assets/assets/') }}/logo.png" width="80" height="80" alt="Harita Logo" style="object-fit: contain;">
        <h1 class="reset-title">Update Password</h1>
        <p class="reset-subtitle">Enter your new password below to reset your account credentials.</p>
      </div>

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        @if ($errors->any())
          <div class="alert alert-danger" style="padding: 0.65rem 0.9rem; border-radius: 8px; background: #fee2e2; color: #b91c1c; font-size: 12.5px; margin-bottom: 1rem;">
            {{ $errors->first() }}
          </div>
        @endif

        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" value="{{ request()->email ?? old('email') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">New Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
      </form>
    </div>

    <!-- Developed by Sitesoch footer -->
    <div class="reset-footer">
      <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
          target="_blank">Sitesoch</a></p>
    </div>
  </div>

</body>
</html>
