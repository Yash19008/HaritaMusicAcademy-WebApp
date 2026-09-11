<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Harita Music Academy')</title>
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}?v=1.2">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/dashboard-layout.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('landing/favicon.png') }}">

    @stack('styles')
</head>

<body>
    <!-- PRELOADER -->
    <div id="preloader" class="preloader-overlay">
        <div class="preloader-content">
            <img src="{{ asset('admin-assets/assets/logo.png') }}" class="preloader-logo" alt="Harita Logo">
            <div class="preloader-spinner"></div>
        </div>
    </div>

    <div class="app-container">
        @include('layouts.main.sidebar')
        @include('layouts.main.header')

        <main class="main-content">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success"
                    style="margin-bottom: 1rem; padding: 1rem; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 0.375rem;">
                    <strong>✓</strong> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger"
                    style="margin-bottom: 1rem; padding: 1rem; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.375rem;">
                    <strong>×</strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger"
                    style="margin-bottom: 1rem; padding: 1rem; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.375rem;">
                    <strong>Validation Errors:</strong>
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

            <footer class="footer" style="margin-top: auto;">
                <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
                        target="_blank">Sitesoch</a> | <a id="policyLink" href="javascript:void(0)"
                        onclick="openPrivacyModal()" style="font-weight: 600; cursor: pointer;">Privacy Policy</a></p>
            </footer>
        </main>
    </div>

    @stack('modals')

    @if (auth()->check() && auth()->user()->hasRole('teacher'))
        <!-- OPPORTUNITY POPUP — Premium Redesign -->
        <style>
            #opportunity-popup {
                display: none;
                position: fixed;
                inset: 0;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.58);
                padding: 20px;
                z-index: 99999;
            }

            #opportunity-popup.active {
                display: flex;
            }

            .opp-modal {
                position: relative;
                width: 100%;
                max-width: 760px;
                background: #ffffff;
                border-radius: 18px;
                padding: 42px 50px 38px;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
                font-family: "Poppins", "Segoe UI", sans-serif;
            }

            .opp-close-btn {
                position: absolute;
                top: 24px;
                right: 28px;
                width: 38px;
                height: 38px;
                border: none;
                background: transparent;
                font-size: 34px;
                font-weight: 300;
                color: #68707a;
                cursor: pointer;
                line-height: 1;
                transition: 0.2s ease;
            }

            .opp-close-btn:hover {
                color: #7c0b19;
            }

            .opp-modal-header {
                display: flex;
                align-items: center;
                gap: 18px;
                margin-bottom: 30px;
                padding-right: 40px;
            }

            .opp-header-icon {
                width: 58px;
                height: 58px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 30px;
                background: #fbe7e9;
                border-radius: 14px;
                flex-shrink: 0;
            }

            .opp-modal-header h2 {
                font-size: 29px;
                line-height: 1.25;
                font-weight: 700;
                color: #7c0b19;
                margin-bottom: 5px;
            }

            .opp-modal-header p {
                font-size: 15px;
                color: #68707a;
                font-weight: 400;
            }

            .opp-class-details {
                background: #f7f8fa;
                border: 1px solid #eef0f2;
                border-radius: 14px;
                padding: 26px;
            }

            .opp-subject-row {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 26px;
            }

            .opp-music-icon {
                width: 58px;
                height: 58px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #f9dfe2;
                color: #8a1724;
                font-size: 32px;
                font-weight: 700;
                flex-shrink: 0;
            }

            .opp-subject-name {
                display: inline-block;
                font-size: 23px;
                font-weight: 700;
                color: #151b24;
                margin-right: 10px;
            }

            .opp-class-badge {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 20px;
                background: #f9dfe2;
                color: #a21c2c;
                font-size: 12px;
                font-weight: 600;
                vertical-align: middle;
            }

            .opp-info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-bottom: 25px;
            }

            .opp-info-item {
                display: flex;
                align-items: flex-start;
                gap: 14px;
            }

            .opp-info-item+.opp-info-item {
                border-left: 1px solid #dfe2e6;
                padding-left: 30px;
            }

            .opp-info-icon {
                font-size: 22px;
                padding-top: 4px;
            }

            .opp-info-label {
                display: block;
                font-size: 13px;
                color: #858c96;
                font-weight: 500;
                margin-bottom: 4px;
            }

            .opp-info-item strong {
                display: block;
                font-size: 15px;
                color: #28313d;
                font-weight: 600;
                white-space: nowrap;
            }

            .opp-info-item small {
                display: block;
                margin-top: 3px;
                color: #8a919b;
                font-size: 12px;
            }

            .opp-bonus-box {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 18px 20px;
                border-radius: 12px;
                background: #fff0f1;
                border: 1px solid #f9dadd;
            }

            .opp-bonus-icon {
                width: 46px;
                height: 46px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                background: #ffffff;
                font-size: 22px;
                flex-shrink: 0;
            }

            .opp-bonus-box h3 {
                color: #8b1725;
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 2px;
            }

            .opp-bonus-box p {
                color: #606873;
                font-size: 12px;
                font-weight: 400;
            }

            .opp-expiry-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 24px;
            }

            .opp-expiry-message {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #8b1725;
                font-size: 13px;
            }

            .opp-expiry-badge {
                padding: 8px 15px;
                border-radius: 20px;
                background: #fbe7e9;
                color: #9b1c2b;
                font-size: 12px;
                font-weight: 600;
            }

            .opp-divider {
                height: 1px;
                background: #e8eaed;
                margin: 25px 0;
            }

            .opp-modal-actions {
                display: grid;
                grid-template-columns: 1fr 1.35fr;
                gap: 16px;
            }

            .opp-btn {
                height: 56px;
                border-radius: 9px;
                font-family: "Poppins", "Segoe UI", sans-serif;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .opp-btn-reject {
                background: #ffffff;
                border: 1px solid #cbd0d6;
                color: #5e6670;
            }

            .opp-btn-reject:hover {
                background: #f5f5f5;
                border-color: #adb3ba;
            }

            .opp-btn-accept {
                border: none;
                background: #820c1a;
                color: #ffffff;
                box-shadow: 0 6px 15px rgba(130, 12, 26, 0.18);
            }

            .opp-btn-accept:hover {
                background: #690914;
                transform: translateY(-1px);
                box-shadow: 0 8px 18px rgba(130, 12, 26, 0.25);
            }

            @media (max-width: 650px) {
                .opp-modal {
                    padding: 30px 22px 25px;
                    border-radius: 14px;
                }

                .opp-modal-header {
                    gap: 12px;
                    margin-bottom: 22px;
                }

                .opp-header-icon {
                    width: 46px;
                    height: 46px;
                    font-size: 23px;
                }

                .opp-modal-header h2 {
                    font-size: 21px;
                }

                .opp-modal-header p {
                    font-size: 12px;
                }

                .opp-class-details {
                    padding: 18px;
                }

                .opp-info-grid {
                    grid-template-columns: 1fr;
                    gap: 18px;
                }

                .opp-info-item+.opp-info-item {
                    border-left: none;
                    border-top: 1px solid #dfe2e6;
                    padding-left: 0;
                    padding-top: 18px;
                }

                .opp-info-item strong {
                    white-space: normal;
                }

                .opp-expiry-row {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 12px;
                }

                .opp-modal-actions {
                    grid-template-columns: 1fr;
                }

                .opp-btn {
                    height: 52px;
                }
            }
        </style>

        <div id="opportunity-popup">
            <div class="opp-modal">

                <!-- Close -->
                <button class="opp-close-btn" id="opp-close" aria-label="Close">&times;</button>

                <!-- Header -->
                <div class="opp-modal-header">
                    <div class="opp-header-icon">🎉</div>
                    <div>
                        <h2>New Class Opportunity</h2>
                        <p>You've been invited to cover this class.</p>
                    </div>
                </div>

                <!-- Class Details -->
                <div class="opp-class-details">

                    <!-- Subject -->
                    <div class="opp-subject-row">
                        <div class="opp-music-icon">♪</div>
                        <div>
                            <span class="opp-subject-name" id="opp-subject"></span>
                            <span class="opp-class-badge">Class</span>
                        </div>
                    </div>

                    <!-- Date / Time -->
                    <div class="opp-info-grid">
                        <div class="opp-info-item">
                            <div class="opp-info-icon">📅</div>
                            <div>
                                <span class="opp-info-label">Date</span>
                                <strong id="opp-date"></strong>
                            </div>
                        </div>
                        <div class="opp-info-item">
                            <div class="opp-info-icon">🕐</div>
                            <div>
                                <span class="opp-info-label">Time</span>
                                <strong id="opp-time"></strong>
                                <small>40 minutes</small>
                            </div>
                        </div>
                    </div>

                    <!-- Bonus -->
                    <div class="opp-bonus-box">
                        <div class="opp-bonus-icon">🎁</div>
                        <div>
                            <h3>&#8377;<span id="opp-bonus"></span> Bonus Reward</h3>
                            <p>Earn an additional bonus for covering this class.</p>
                        </div>
                    </div>

                </div>

                <!-- Expiry -->
                <div class="opp-expiry-row">
                    <div class="opp-expiry-message">
                        <span>⏱</span>
                        <strong>Please respond within 15 minutes</strong>
                    </div>
                    <div class="opp-expiry-badge" id="opp-expiry-badge">Expires in 15:00</div>
                </div>

                <!-- Divider -->
                <div class="opp-divider"></div>

                <!-- Actions -->
                <div class="opp-modal-actions">
                    <button class="opp-btn opp-btn-reject" id="opp-reject">Reject</button>
                    <button class="opp-btn opp-btn-accept" id="opp-accept">✓ Accept Class &nbsp;→</button>
                </div>

            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let currentOppId = null;
                let expiryTimer = null;
                let expirySeconds = 15 * 60;

                function startExpiryCountdown() {
                    clearInterval(expiryTimer);
                    expirySeconds = 15 * 60;
                    updateExpiryBadge();
                    expiryTimer = setInterval(function() {
                        expirySeconds--;
                        if (expirySeconds <= 0) {
                            clearInterval(expiryTimer);
                            closePopup();
                        } else {
                            updateExpiryBadge();
                        }
                    }, 1000);
                }

                function updateExpiryBadge() {
                    const m = String(Math.floor(expirySeconds / 60)).padStart(2, '0');
                    const s = String(expirySeconds % 60).padStart(2, '0');
                    const badge = document.getElementById('opp-expiry-badge');
                    if (badge) badge.textContent = 'Expires in ' + m + ':' + s;
                }

                function checkOpportunity() {
                    if (document.getElementById('opportunity-popup').classList.contains('active')) return;

                    fetch('{{ route('teacher.opportunities.current') }}')
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.id) showOpportunity(data);
                        })
                        .catch(err => console.error(err));
                }

                function showOpportunity(data) {
                    currentOppId = data.id;
                    document.getElementById('opp-subject').innerText = data.subject;
                    document.getElementById('opp-date').innerText = data.date;
                    document.getElementById('opp-time').innerText = data.time;
                    document.getElementById('opp-bonus').innerText = data.bonus;
                    document.getElementById('opportunity-popup').classList.add('active');
                    startExpiryCountdown();
                }

                function closePopup() {
                    document.getElementById('opportunity-popup').classList.remove('active');
                    currentOppId = null;
                    clearInterval(expiryTimer);
                }

                // Close button
                document.getElementById('opp-close').addEventListener('click', closePopup);

                // Accept
                document.getElementById('opp-accept').addEventListener('click', function() {
                    if (!currentOppId) return;
                    const btn = this;
                    btn.innerText = 'Accepting…';
                    btn.disabled = true;

                    fetch(`/teacher/opportunities/${currentOppId}/accept`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            closePopup();
                            btn.innerText = '✓ Accept Class →';
                            btn.disabled = false;
                            if (data.success) {
                                if (typeof showToast !== 'undefined') showToast(
                                    'Class accepted! Google Calendar updated.', 'success');
                                else alert('Success! The class has been assigned to you.');
                                window.location.reload();
                            } else {
                                if (typeof showToast !== 'undefined') showToast(data.message ||
                                    'Someone else already accepted this opportunity.', 'error');
                                else alert(data.message ||
                                    'Someone else already accepted this opportunity.');
                            }
                        })
                        .catch(() => {
                            btn.innerText = '✓ Accept Class →';
                            btn.disabled = false;
                            closePopup();
                        });
                });

                // Reject
                document.getElementById('opp-reject').addEventListener('click', function() {
                    if (!currentOppId) return;
                    fetch(`/teacher/opportunities/${currentOppId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    closePopup();
                });

                // Poll every 5 seconds
                setInterval(checkOpportunity, 5000);
            });
        </script>
    @endif


    @if (auth()->check() &&
            auth()->user()->hasRole('student') &&
            auth()->user()->student &&
            auth()->user()->student->credits <= 2 &&
            is_null(auth()->user()->student->renewal_interest))
        <div id="renewal-popup" class="modal-overlay"
            style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999;">
            <div class="modal-content"
                style="background: white; padding: 2rem; border-radius: 12px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                <h2 style="margin-bottom: 1rem; color: #dc2626;">Low Credits Alert!</h2>
                <p style="margin-bottom: 1.5rem; color: #4b5563; font-size: 1.1rem;">
                    You have only <strong>{{ auth()->user()->student->credits }} classes left!</strong><br><br>
                    Are you interested in purchasing a new package or enrolling in another course?
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button id="renewal-yes" class="btn btn-primary" style="flex: 1;">Yes, I'm interested!</button>
                    <button id="renewal-no" class="btn btn-secondary" style="flex: 1;">No, thanks</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function submitRenewalInterest(interest) {
                    const btnYes = document.getElementById('renewal-yes');
                    const btnNo = document.getElementById('renewal-no');

                    if (interest === 'interested') {
                        btnYes.innerText = 'Submitting...';
                        btnYes.disabled = true;
                        btnNo.disabled = true;
                    } else {
                        btnNo.innerText = 'Submitting...';
                        btnYes.disabled = true;
                        btnNo.disabled = true;
                    }

                    fetch('{{ route('student.renewal-interest.submit') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                interest: interest
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (interest === 'interested') {
                                    alert(
                                        'Thank you! Our admin team has been notified and will contact you shortly.'
                                    );
                                }
                                document.getElementById('renewal-popup').style.display = 'none';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            document.getElementById('renewal-popup').style.display = 'none';
                        });
                }

                document.getElementById('renewal-yes').addEventListener('click', function() {
                    submitRenewalInterest('interested');
                });

                document.getElementById('renewal-no').addEventListener('click', function() {
                    submitRenewalInterest('declined');
                });
            });
        </script>
    @endif

    <!-- PRIVACY POLICY MODAL SCOPED CSS -->
    <style>
        /* Premium Policy Modal Styles */
        #privacy-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 99999;
            backdrop-filter: blur(4px);
        }

        .policy-modal-content {
            background: #fff;
            border-radius: 16px;
            max-width: 680px;
            width: 94%;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease-out forwards;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .policy-modal-header {
            background: var(--primary);
            color: #fff;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .policy-modal-title {
            margin: 0;
            font-family: var(--font-serif, serif);
            font-size: 1.35rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ffffff !important;
        }

        .policy-modal-close {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .policy-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .policy-modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4b5563;
        }

        .premium-policy-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .premium-policy-list li {
            position: relative;
            padding: 1rem 1rem 1rem 3rem;
            border-bottom: 1px solid #f3f4f6;
            counter-increment: policy-counter;
        }

        .premium-policy-list li:nth-child(even) {
            background-color: #fcfcfc;
        }

        .premium-policy-list li:last-child {
            border-bottom: none;
        }

        .premium-policy-list li::before {
            content: counter(policy-counter);
            position: absolute;
            left: 0.75rem;
            top: 1rem;
            width: 24px;
            height: 24px;
            background: rgba(81, 4, 14, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .premium-policy-list strong {
            color: var(--primary);
            font-weight: 600;
        }

        .policy-modal-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            text-align: center;
        }
    </style>

    <!-- PRIVACY POLICY MODAL -->
    <div id="privacy-modal" class="modal-overlay">
        <div class="policy-modal-content">
            <div class="policy-modal-header">
                <h4 class="policy-modal-title" id="privacy-modal-title">
                    <span id="privacy-modal-icon">📜</span>
                    <span id="privacy-modal-title-text">Academy Policy</span>
                </h4>
                <button onclick="closePrivacyModal()" class="policy-modal-close" aria-label="Close">&times;</button>
            </div>
            <div id="privacy-modal-body" class="policy-modal-body"></div>
            <div class="policy-modal-footer">
                <button onclick="closePrivacyModal()" class="btn btn-primary"
                    style="padding: 0.6rem 2rem; border-radius: 8px;">I Understand & Agree</button>
            </div>
        </div>
    </div>

    <script>
        const studentPolicyContent =
            `<ol class="premium-policy-list"><li style="margin-bottom:0.75rem;"><strong>Acceptance of Academy Policies</strong>: By registering with Harita Music Academy, every student agrees to comply with all Academy policies, guidelines, and future updates. Continued use of Academy services constitutes acceptance of these policies.</li><li style="margin-bottom:0.75rem;"><strong>Student Eligibility</strong>: Students must provide accurate personal information during registration. Any false or misleading information may result in account suspension or termination.</li><li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Every student is expected to maintain courtesy, respect, and professionalism towards teachers, Academy staff, and fellow students at all times.</li><li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Abusive language, harassment, threats, bullying, discrimination, inappropriate behaviour, or any action that disrupts the learning environment will not be tolerated.</li><li style="margin-bottom:0.75rem;"><strong>Professional Classroom Etiquette</strong>: Students should attend classes with proper discipline, remain attentive, avoid unnecessary interruptions, and contribute positively to the learning environment.</li><li style="margin-bottom:0.75rem;"><strong>Attendance</strong>: Regular attendance is essential for consistent progress. Students are responsible for attending every scheduled class on time.</li><li style="margin-bottom:0.75rem;"><strong>Punctuality</strong>: Students are advised to join the class at least 5 minutes before the scheduled time. Late entry may reduce the effective learning duration and repeated delays may affect learning progress.</li><li style="margin-bottom:0.75rem;"><strong>Class Cancellation &amp; Rescheduling</strong>: Class cancellation or rescheduling requests must be submitted at least 10 hours before the scheduled class. Requests received after this period will not be accepted.</li><li style="margin-bottom:0.75rem;"><strong>No Show Policy</strong>: Failure to attend a scheduled class without prior notice will be recorded as a No Show. The class credit will be considered consumed and no refund, replacement, or rescheduling will be provided.</li><li style="margin-bottom:0.75rem;"><strong>Class Credits</strong>: Class credits are personal, non-transferable, and cannot be exchanged for cash. Credits remain valid only within the purchased package validity.</li><li style="margin-bottom:0.75rem;"><strong>Refund Policy</strong>: Course fees, class credits, and purchased packages are generally non-refundable unless specifically approved under the Academy's official Refund Policy.</li><li style="margin-bottom:0.75rem;"><strong>Payment Responsibility</strong>: Students are responsible for ensuring timely payment of all applicable fees. Access to Academy services may be restricted until outstanding payments are cleared.</li><li style="margin-bottom:0.75rem;"><strong>Demo Class Policy</strong>: Demo classes are intended solely for evaluation purposes and are governed by the Academy's demo class guidelines.</li><li style="margin-bottom:0.75rem;"><strong>Learning Environment</strong>: Students must attend classes from a quiet, distraction-free environment with a stable internet connection, functional microphone, and suitable learning setup.</li><li style="margin-bottom:0.75rem;"><strong>Recording &amp; Copyright</strong>: Recording, downloading, sharing, reproducing, or distributing any Academy class, study material, or digital content without prior written permission is strictly prohibited.</li><li style="margin-bottom:0.75rem;"><strong>Study Material Usage</strong>: All PDFs, recordings, videos, exercises, and learning resources are provided exclusively for personal educational use and remain the intellectual property of Harita Music Academy.</li><li style="margin-bottom:0.75rem;"><strong>Communication</strong>: All Academy-related communication should take place through authorised Academy platforms. Respectful communication is expected at all times.</li><li style="margin-bottom:0.75rem;"><strong>Privacy &amp; Account Security</strong>: Students are responsible for maintaining the confidentiality of their login credentials. Account sharing is strictly prohibited.</li><li style="margin-bottom:0.75rem;"><strong>Technical Responsibility</strong>: Students are responsible for maintaining a reliable internet connection and compatible devices. Technical issues on the student's side shall not qualify for compensation or replacement classes.</li><li style="margin-bottom:0.75rem;"><strong>Parent/Guardian Responsibility</strong>: For minor students, parents or guardians are expected to maintain respectful communication with Academy staff and support a positive learning environment.</li><li style="margin-bottom:0.75rem;"><strong>Weekly Feedback</strong>: Students may be invited to submit weekly feedback after attending classes. Constructive feedback helps improve the overall learning experience.</li><li style="margin-bottom:0.75rem;"><strong>Platform Misuse</strong>: Unauthorised access, impersonation, spam, fraudulent activity, or misuse of Academy systems is strictly prohibited.</li><li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Violation of Academy policies may result in verbal warnings, written warnings, temporary suspension, cancellation of class credits, or permanent account termination, depending on the severity of the violation.</li><li style="margin-bottom:0.75rem;"><strong>Academy Authority</strong>: Harita Music Academy reserves the right to modify policies, schedules, faculty assignments, fees, or operational procedures whenever necessary. The Academy's decision regarding policy interpretation and disciplinary matters shall remain final and binding.</li></ol>`;

        const teacherPolicyContent =
            `<ol class="premium-policy-list"><li style="margin-bottom:0.75rem;"><strong>Acceptance of Policies</strong>: By joining Harita Music Academy, every teacher agrees to comply with all Academy policies, operational guidelines, and future updates.</li><li style="margin-bottom:0.75rem;"><strong>Professional Conduct</strong>: Teachers shall maintain the highest standards of professionalism, integrity, and ethical behaviour at all times.</li><li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Teachers must treat every student, parent, colleague, and Academy representative with dignity, patience, and respect.</li><li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Harassment, abusive language, discrimination, intimidation, threats, inappropriate behaviour, or unprofessional conduct will not be tolerated under any circumstances.</li><li style="margin-bottom:0.75rem;"><strong>Punctuality</strong>: Teachers must join every scheduled class at least 5 minutes before the class begins.</li><li style="margin-bottom:0.75rem;"><strong>Class Responsibility</strong>: Every scheduled class must be conducted with proper preparation, discipline, and commitment to the approved curriculum.</li><li style="margin-bottom:0.75rem;"><strong>Attendance Submission</strong>: Attendance must be marked accurately immediately after each class. False attendance records are considered a serious policy violation.</li><li style="margin-bottom:0.75rem;"><strong>Leave Request</strong>: Planned leave or class cancellation requests must be submitted at least 6 hours before the scheduled class for approval.</li><li style="margin-bottom:0.75rem;"><strong>Teacher No Show</strong>: Failure to conduct an assigned class without prior approval will be recorded as a Teacher No Show. A ₹500 penalty may be applied for each confirmed violation.</li><li style="margin-bottom:0.75rem;"><strong>Late Joining</strong>: Repeated late joining affects student learning and may lead to warnings, performance review, or disciplinary action.</li><li style="margin-bottom:0.75rem;"><strong>Class Cancellation</strong>: Teachers shall not cancel classes without valid reasons and prior approval from the Academy except in genuine emergencies.</li><li style="margin-bottom:0.75rem;"><strong>Student Progress</strong>: Teachers are responsible for monitoring student progress, maintaining lesson continuity, and providing constructive guidance.</li><li style="margin-bottom:0.75rem;"><strong>Teaching Quality</strong>: Every class must meet the Academy's expected standards of quality, professionalism, and student engagement.</li><li style="margin-bottom:0.75rem;"><strong>Communication</strong>: All communication with students and parents must remain professional and should take place only through authorised Academy channels.</li><li style="margin-bottom:0.75rem;"><strong>Student Privacy</strong>: Teachers shall protect the confidentiality of all student information, academic records, and personal data.</li><li style="margin-bottom:0.75rem;"><strong>Recording &amp; Intellectual Property</strong>: Academy curriculum, recordings, lesson plans, presentations, PDFs, and all educational resources remain the exclusive intellectual property of Harita Music Academy and may not be copied, distributed, or used outside the Academy without written permission.</li><li style="margin-bottom:0.75rem;"><strong>Conflict of Interest</strong>: Teachers shall not encourage, solicit, or transfer Academy students to personal tuition, private classes, or competing platforms.</li><li style="margin-bottom:0.75rem;"><strong>Financial Conduct</strong>: Teachers are strictly prohibited from accepting direct payments, gifts in exchange for services, or conducting private financial transactions with Academy students.</li><li style="margin-bottom:0.75rem;"><strong>Professional Appearance</strong>: Teachers are expected to maintain a neat, professional appearance and ensure an appropriate teaching environment during online classes.</li><li style="margin-bottom:0.75rem;"><strong>Technical Responsibility</strong>: Teachers must ensure a stable internet connection, clear audio, and suitable teaching equipment before every class.</li><li style="margin-bottom:0.75rem;"><strong>Performance Review</strong>: Teaching quality, punctuality, attendance, student feedback, and overall professionalism may be reviewed periodically by the Academy.</li><li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Depending on the severity of the violation, disciplinary actions may include verbal warning, written warning, ₹500 penalty, temporary suspension, payment review, or permanent termination of association.</li><li style="margin-bottom:0.75rem;"><strong>Emergency Situations</strong>: In exceptional emergencies, teachers must inform the Academy immediately so that alternative teaching arrangements can be made.</li><li style="margin-bottom:0.75rem;"><strong>Academy Rights</strong>: Harita Music Academy reserves the right to modify schedules, class allocations, operational procedures, and teaching assignments whenever required.</li><li style="margin-bottom:0.75rem;"><strong>Final Decision</strong>: All decisions regarding teacher performance, disciplinary matters, penalties, suspensions, and policy interpretation shall be made solely by Harita Music Academy and shall remain final and binding.</li></ol>`;

        const staffPolicyContent =
            `<ol class="premium-policy-list"><li style="margin-bottom:0.75rem;"><strong>Acceptance of Policy</strong>: All staff members are required to comply with the Academy's policies, procedures, and ethical standards.</li><li style="margin-bottom:0.75rem;"><strong>Professional Conduct</strong>: Every employee shall perform their duties with honesty, professionalism, integrity, and accountability.</li><li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Respectful communication with students, parents, teachers, colleagues, and management is mandatory at all times.</li><li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Abusive language, harassment, discrimination, bullying, threats, or any inappropriate behaviour will lead to disciplinary action.</li><li style="margin-bottom:0.75rem;"><strong>Punctuality &amp; Attendance</strong>: Employees are expected to report on time, complete assigned duties responsibly, and maintain regular attendance.</li><li style="margin-bottom:0.75rem;"><strong>Confidentiality</strong>: All student, teacher, financial, operational, and business information must remain strictly confidential during and after employment.</li><li style="margin-bottom:0.75rem;"><strong>Honest Communication</strong>: False promises, misleading information, or unauthorised commitments to students or parents are strictly prohibited.</li><li style="margin-bottom:0.75rem;"><strong>Sales &amp; Admission Ethics</strong>: Admissions and counselling must be conducted honestly. Misrepresentation for personal targets or incentives is not permitted.</li><li style="margin-bottom:0.75rem;"><strong>Financial Integrity</strong>: No employee may collect personal payments, accept unauthorised cash, or misuse Academy funds or resources.</li><li style="margin-bottom:0.75rem;"><strong>Academy Property</strong>: All documents, software, login credentials, equipment, and digital resources remain the property of Harita Music Academy and must be protected at all times.</li><li style="margin-bottom:0.75rem;"><strong>Conflict of Interest</strong>: Employees shall not promote competing businesses, misuse Academy contacts, or recruit Academy students or teachers for personal benefit.</li><li style="margin-bottom:0.75rem;"><strong>Data &amp; System Security</strong>: Unauthorised access, sharing, copying, or misuse of Academy data or systems is strictly prohibited.</li><li style="margin-bottom:0.75rem;"><strong>Social Media &amp; Public Conduct</strong>: Employees shall not publish confidential information or make public statements that may harm the Academy's reputation.</li><li style="margin-bottom:0.75rem;"><strong>Performance &amp; Responsibility</strong>: Employees are expected to perform their assigned responsibilities efficiently, maintain work quality, and cooperate with their team.</li><li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Depending on the seriousness of the violation, disciplinary action may include a verbal warning, written warning, suspension, salary deduction where legally applicable, or termination of employment.</li><li style="margin-bottom:0.75rem;"><strong>Academy Authority</strong>: Harita Music Academy reserves the right to modify policies, assign responsibilities, review employee performance, and take disciplinary action whenever necessary. The Academy's decision shall be final and binding.</li></ol>`;

        @if (auth()->check() && auth()->user()->hasRole('admin'))
            var ACTIVE_POLICY = staffPolicyContent;
            var ACTIVE_POLICY_TITLE = 'Non-Teaching Staff Policy';
            var ACTIVE_POLICY_ICON = '🏢';
        @elseif (auth()->check() && auth()->user()->hasRole('teacher'))
            var ACTIVE_POLICY = teacherPolicyContent;
            var ACTIVE_POLICY_TITLE = 'Teacher Policy';
            var ACTIVE_POLICY_ICON = '👩‍🏫';
        @elseif (auth()->check() && auth()->user()->hasRole('student'))
            var ACTIVE_POLICY = studentPolicyContent;
            var ACTIVE_POLICY_TITLE = 'Student Policy';
            var ACTIVE_POLICY_ICON = '🎓';
        @else
            var ACTIVE_POLICY = studentPolicyContent;
            var ACTIVE_POLICY_TITLE = 'Student Policy';
            var ACTIVE_POLICY_ICON = '🎓';
        @endif

        function openPrivacyModal() {
            document.getElementById('privacy-modal-title-text').innerText = ACTIVE_POLICY_TITLE;
            document.getElementById('privacy-modal-icon').innerText = ACTIVE_POLICY_ICON;
            document.getElementById('privacy-modal-body').innerHTML = ACTIVE_POLICY;
            document.getElementById('privacy-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closePrivacyModal() {
            document.getElementById('privacy-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        document.getElementById('privacy-modal').addEventListener('click', function(e) {
            if (e.target === this) closePrivacyModal();
        });
    </script>

    <script src="{{ asset('admin-assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
