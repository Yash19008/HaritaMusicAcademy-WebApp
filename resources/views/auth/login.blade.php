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
                <img src="{{ asset('admin-assets/assets/') }}/logo.png" width="80" height="80" alt="Harita Logo"
                    style="object-fit: contain;">
                <h1 class="login-title">Harita Music Academy</h1>
                <p class="login-subtitle">Academy Portal & Administrative Panel</p>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger"
                        style="padding: 0.65rem 0.9rem; border-radius: 8px; background: #fee2e2; color: #b91c1c; font-size: 12.5px; margin-bottom: 1rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div
                        style="padding: 0.65rem 0.9rem; border-radius: 8px; background: #d1fae5; color: #065f46; font-size: 12.5px; margin-bottom: 1rem;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                        placeholder="Registered email address" value="{{ old('email') }}" required
                        autocomplete="email">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                        required autocomplete="current-password">
                </div>

                <div class="d-flex align-center justify-between mb-3">
                    <label class="checkbox-label" style="font-size:11.5px;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="btn-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Sign In</button>

                <div style="text-align: center; margin: 1rem 0; position: relative;">
                    <div style="border-bottom: 1px solid #e2e8f0; position: absolute; width: 100%; top: 50%;"></div>
                    <span
                        style="background: var(--bg-card); padding: 0 10px; color: var(--text-muted); font-size: 11px; position: relative; font-weight: 500;">OR</span>
                </div>

                <a href="{{ route('auth.google') }}" class="btn w-100"
                    style="background: #ffffff; border: 1px solid #e2e8f0; color: #334155; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500; font-size: 13px; transition: all 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 48 48" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M47.532 24.5528C47.532 22.9214 47.3997 21.2811 47.1175 19.6761H24.48V28.9181H37.4434C36.9055 31.8988 35.177 34.5356 32.6461 36.2111V42.2078H40.3801C44.9217 38.0278 47.532 31.8547 47.532 24.5528Z"
                            fill="#4285F4" />
                        <path
                            d="M24.48 48.0016C30.9529 48.0016 36.4116 45.8764 40.3888 42.2078L32.6549 36.2111C30.5031 37.675 27.7253 38.5056 24.48 38.5056C18.2058 38.5056 12.8315 34.2798 10.8695 28.5922H2.8956V34.7869C7.18471 43.2982 15.4116 48.0016 24.48 48.0016Z"
                            fill="#34A853" />
                        <path
                            d="M10.8695 28.5922C10.3405 27.0181 10.0494 25.3533 10.0494 23.6708C10.0494 21.9883 10.3405 20.3235 10.8695 18.7494V12.5547H2.8956C1.06176 16.2144 0 20.2529 0 24.48C0 28.7071 1.06176 32.7456 2.8956 36.4053L10.8695 28.5922Z"
                            fill="#FBBC04" />
                        <path
                            d="M24.48 8.84715C27.9942 8.84715 31.1554 10.0535 33.6425 12.4342L40.5566 5.51862C36.394 1.65651 30.9353 0 24.48 0C15.4116 0 7.18471 4.7034 2.8956 13.2147L10.8695 19.4094C12.8315 13.7218 18.2058 8.84715 24.48 8.84715Z"
                            fill="#EA4335" />
                    </svg>
                    Continue with Google
                </a>
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
            if (pre) setTimeout(() => {
                pre.style.opacity = 0;
                setTimeout(() => pre.remove(), 400);
            }, 300);
        });
    </script>
</body>

</html>
