<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Harita Music Academy</title>
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

    .forgot-container {
      width: 100%;
      max-width: 420px;
      animation: slideUp 0.6s var(--transition-cubic) forwards;
    }

    .forgot-card {
      background-color: var(--bg-card);
      border-radius: var(--radius-lg);
      border: 1px solid #e2e8f0;
      box-shadow: var(--shadow-lg);
      padding: 2.25rem 2.5rem;
      position: relative;
    }

    .forgot-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: var(--primary);
      border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .forgot-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .forgot-logo svg,
    .forgot-logo img {
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

    .forgot-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-align: center;
      color: var(--primary-dark);
      margin-bottom: 0.35rem;
    }

    .forgot-subtitle {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-align: center;
      margin-bottom: 1.5rem;
      line-height: 1.45;
      font-weight: 500;
    }

    .success-panel {
      display: none;
      text-align: center;
    }

    .success-icon {
      width: 50px;
      height: 50px;
      background-color: var(--success-bg);
      color: var(--success);
      border-radius: var(--radius-round);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin: 0 auto 0.75rem;
    }

    .forgot-footer {
      margin-top: 1.5rem;
      color: var(--text-muted);
      font-size: 11px;
      font-weight: 500;
      text-align: center;
      line-height: 1.45;
    }

    .forgot-footer a {
      color: var(--primary);
      font-weight: 600;
    }

    .forgot-footer a:hover {
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

  <div class="forgot-container">
    <div class="forgot-card">

      <!-- Primary Input Form -->
      <div id="formPanel">
        <div class="forgot-logo">
          <img src="{{ asset('admin-assets/assets/') }}/logo.png" width="80" height="80" alt="Harita Logo" style="object-fit: contain;">
          <h1 class="forgot-title">Reset Password</h1>
          <p class="forgot-subtitle">Enter your registered email address and we'll send you instructions to reset your
            password.</p>
        </div>

        <form id="forgotForm" onsubmit="handleReset(event)">
          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" class="form-control" placeholder="name@example.com" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Instructions</button>

          <div class="text-center">
            <a href="{{ route('login') }}" class="btn-link">Back to Sign In</a>
          </div>
        </form>
      </div>

      <!-- Success Screen -->
      <div id="successPanel" class="success-panel">
        <div class="success-icon">✓</div>
        <h1 class="forgot-title">Check Your Email</h1>
        <p class="forgot-subtitle">We have sent password recovery instructions to <strong id="sentEmail">your
            email</strong>.</p>
        <button onclick="window.location.href='{{ route('login') }}'" class="btn btn-primary w-100 mb-2">Return to Login</button>
      </div>

    </div>

    <!-- Developed by Sitesoch footer -->
    <div class="forgot-footer">
      <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
          target="_blank">Sitesoch</a></p>
    </div>
  </div>

  <script src="{{ asset('admin-assets/js/') }}/app.js"></script>
  <script>
    function handleReset(event) {
      event.preventDefault();
      const email = document.getElementById('email').value;
      document.getElementById('sentEmail').textContent = email;
      document.getElementById('formPanel').style.display = 'none';
      document.getElementById('successPanel').style.display = 'block';
    }
  </script>
</body>

</html>