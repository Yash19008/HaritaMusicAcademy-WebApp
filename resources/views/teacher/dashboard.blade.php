@extends('layouts.main')
@section('page', 'dashboard')

@push('styles')
    <style>
        /* Unique Dashboard Accent Elements */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--text-white);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--primary-light);
        }

        .welcome-banner::after {
            content: "";
            position: absolute;
            right: -50px;
            bottom: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, var(--secondary) 0%, rgba(201, 174, 135, 0) 70%);
            opacity: 0.15;
            pointer-events: none;
        }

        .welcome-text h2 {
            color: var(--secondary-light);
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        .stat-card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .stat-card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stat-card-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            font-size: 1.15rem;
            border-radius: var(--radius-md);
            background-color: var(--border-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card:hover .stat-icon {
            background-color: var(--primary);
            color: var(--text-white);
            transform: scale(1.05);
            transition: all 0.3s;
        }

        .chart-container {
            background-color: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            height: 250px;
        }

        /* Student-Specific Ref Styles */
        .student-dashboard-layout {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1200px) {
            .student-dashboard-layout {
                grid-template-columns: 1fr;
            }
        }

        .student-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .student-card-banner {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background: linear-gradient(135deg, rgba(20, 85, 61, 0.02) 0%, rgba(201, 174, 135, 0.05) 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1rem;
        }

        .student-card-banner img {
            width: 70px;
            height: 70px;
            border-radius: var(--radius-round);
            object-fit: cover;
            border: 2px solid var(--secondary);
        }

        .student-class-box {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--primary);
            padding: 1.25rem;
            border-radius: var(--radius-md);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.75rem;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .student-class-box:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .student-class-box .btn {
            flex: 1;
            text-align: center;
            justify-content: center;
        }

        .schedule-grid-cols {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .teacher-mini-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.85rem;
            padding-top: 0.85rem;
            border-top: 1px solid var(--border-color);
        }

        .achievement-badge-container {
            display: flex;
            gap: 0.85rem;
            justify-content: space-around;
            margin-top: 0.85rem;
        }

        .achievement-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .achievement-circle {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-round);
            border: 2px solid var(--secondary);
            background-color: var(--bg-main);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-dark);
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
            box-shadow: var(--shadow-sm);
        }

        .recording-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        .recording-item:last-child {
            border-bottom: none;
        }

        .btn-play {
            background-color: var(--border-light);
            border: none;
            width: 28px;
            height: 28px;
            border-radius: var(--radius-round);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--primary);
            transition: all 0.2s;
        }

        .btn-play:hover {
            background-color: var(--primary);
            color: var(--text-white);
        }
    </style>
@endpush

@section('content')
    <div id="teacherDashboardView" class="slide-up">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>Welcome back, {{ $teacher->user->name ?? 'Teacher' }}! 🎻</h2>
                <p>Your students are waiting. Keep teaching, great music changes lives.</p>
            </div>
        </div>

        <div class="grid grid-12 gap-4 mb-4">
            <div class="card p-3">
                <h4 class="font-semibold text-primary mb-3">Today's Class Schedule</h4>
                <div class="schedule-grid-cols">
                    @php $now = now(); @endphp
                    @foreach ($todayClasses as $class)
                        <div class="student-class-box">
                            <div class="d-flex justify-between align-center mb-2">
                                <span class="badge badge-primary">{{ $class->starts_at->format('h:i A') }}</span>
                                <span class="text-muted" style="font-size: 0.8rem;">{{ $class->duration_minutes }}
                                    mins</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-main" style="margin-bottom: 0.25rem;">
                                    {{ $class->student->user->name ?? 'N/A' }}</h5>
                                <p class="text-muted" style="font-size: 0.85rem;">{{ $class->instrument }}</p>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                @php
                                    $minutesUntilClass = $now->diffInMinutes($class->starts_at, false);
                                    $canJoin =
                                        $minutesUntilClass <= 15 &&
                                        $now->isBefore(
                                            $class->ends_at ??
                                                $class->starts_at->copy()->addMinutes($class->duration_minutes ?? 40),
                                        );
                                @endphp
                                @if ($canJoin)
                                    <a href="{{ $class->teacher_join_url }}" target="_blank" class="btn btn-primary btn-sm"
                                        style="flex:1; text-align:center; text-decoration:none;">Start Class</a>
                                @else
                                    <button class="btn btn-primary btn-sm"
                                        style="flex:1; text-align:center; opacity:0.5; cursor:not-allowed;"
                                        title="You can join 15 minutes before the class starts." disabled>Start
                                        Class</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if (isset($todayDemos) && $todayDemos->count() > 0)
            <div class="grid grid-12 gap-4 mb-4">
                <div class="card p-3" style="border-left: 4px solid var(--warning);">
                    <h4 class="font-semibold text-warning mb-3">Today's Demo Classes</h4>
                    <div class="schedule-grid-cols">
                        @foreach ($todayDemos as $demo)
                            <div class="student-class-box" style="border-left-color: var(--warning);">
                                <div class="d-flex justify-between align-center mb-2">
                                    <span class="badge badge-primary">{{ $demo->scheduled_at->format('h:i A') }}</span>
                                    <span class="text-muted" style="font-size: 0.8rem;">{{ $demo->duration_minutes }}
                                        mins</span>
                                </div>
                                <div>
                                    <h5 class="font-bold text-main" style="margin-bottom: 0.25rem;">
                                        {{ $demo->student_name }}</h5>
                                    <p class="text-muted" style="font-size: 0.85rem;">{{ $demo->instrument }} (Demo)</p>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    @php
                                        $minutesUntilClass = $now->diffInMinutes($demo->scheduled_at, false);
                                        $canJoin =
                                            $minutesUntilClass <= 15 &&
                                            $now->isBefore(
                                                $demo->scheduled_at->copy()->addMinutes($demo->duration_minutes ?? 40),
                                            );
                                    @endphp
                                    @if ($canJoin)
                                        <a href="{{ $demo->teacher_join_url }}" onclick="showDemoPopup(event, this.href)"
                                            class="btn btn-primary btn-sm"
                                            style="flex:1; text-align:center; text-decoration:none;">Start Demo</a>
                                    @else
                                        <button class="btn btn-primary btn-sm"
                                            style="flex:1; text-align:center; opacity:0.5; cursor:not-allowed;"
                                            title="You can join 15 minutes before the class starts." disabled>Start
                                            Demo</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
                target="_blank">Sitesoch</a></p>
    </footer>

    <div id="demoGuidelinePopup" class="demo-popup-overlay" style="display: none;">
        <div class="demo-popup-glass">
            <div class="demo-popup-top">
                <div class="demo-icon-ring">
                    <span class="demo-popup-icon">🚀</span>
                </div>
                <h3 class="demo-popup-title">Demo Class Flow</h3>
                <p class="demo-popup-subtitle">Please follow this structured roadmap for a successful trial session.</p>
            </div>

            <div class="demo-flow-cards">
                <div class="flow-card">
                    <div class="flow-time">5 mins</div>
                    <div class="flow-desc"><strong>Introduction:</strong> Build rapport and understand the student's
                        background.</div>
                </div>
                <div class="flow-card">
                    <div class="flow-time highlight">10 mins</div>
                    <div class="flow-desc"><strong>Voice Assessment:</strong> Evaluate their current vocal or instrumental
                        skills.</div>
                </div>
                <div class="flow-card">
                    <div class="flow-time highlight-main">20 mins</div>
                    <div class="flow-desc"><strong>Teaching Session:</strong> Deliver a high-value, engaging mini-lesson.
                    </div>
                </div>
                <div class="flow-card">
                    <div class="flow-time">5 mins</div>
                    <div class="flow-desc"><strong>Roadmap:</strong> Explain what they will achieve in the next few months
                        to close.</div>
                </div>
            </div>

            <div class="demo-popup-action">
                <div class="redirect-spinner"></div>
                <p>Redirecting to Google Meet in <strong id="demoPopupTimer" class="timer-text">10</strong> seconds...</p>
            </div>
        </div>
    </div>

    <style>
        .demo-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInPopup 0.3s ease-out;
        }

        .demo-popup-glass {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            animation: scaleUpPopup 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .demo-popup-glass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #d4af37, #f5d76e, #d4af37);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }

        .demo-popup-top {
            text-align: center;
            margin-bottom: 25px;
        }

        .demo-icon-ring {
            width: 70px;
            height: 70px;
            background: #fff8e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 0 0 8px rgba(212, 175, 55, 0.1);
        }

        .demo-popup-icon {
            font-size: 2.2rem;
        }

        .demo-popup-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 5px 0;
        }

        .demo-popup-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0;
        }

        .demo-flow-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 30px;
        }

        .flow-card {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            transition: transform 0.2s;
        }

        .flow-card:hover {
            transform: translateX(5px);
            border-color: #cbd5e1;
        }

        .flow-time {
            background: #e2e8f0;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 8px;
            min-width: 75px;
            text-align: center;
            margin-right: 15px;
        }

        .flow-time.highlight {
            background: #fef3c7;
            color: #d97706;
        }

        .flow-time.highlight-main {
            background: #dbeafe;
            color: #2563eb;
        }

        .flow-desc {
            color: #334155;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .flow-desc strong {
            color: #0f172a;
        }

        .demo-popup-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #f1f5f9;
            padding: 15px;
            border-radius: 12px;
        }

        .demo-popup-action p {
            margin: 0;
            color: #475569;
            font-weight: 500;
        }

        .timer-text {
            color: #d4af37;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .redirect-spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #cbd5e1;
            border-top-color: #d4af37;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes fadeInPopup {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleUpPopup {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes gradientMove {
            0% {
                background-position: 100% 0;
            }

            100% {
                background-position: -100% 0;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        let demoPopupInterval;

        function showDemoPopup(event, url) {
            event.preventDefault();

            const popup = document.getElementById('demoGuidelinePopup');
            const timerSpan = document.getElementById('demoPopupTimer');

            if (!popup) return;

            clearInterval(demoPopupInterval);
            popup.style.display = 'flex';

            let secondsLeft = 10;
            timerSpan.textContent = secondsLeft;

            demoPopupInterval = setInterval(() => {
                secondsLeft--;
                timerSpan.textContent = secondsLeft;

                if (secondsLeft <= 0) {
                    clearInterval(demoPopupInterval);
                    timerSpan.parentElement.innerHTML = "Redirecting now...";

                    let newWin = window.open(url, '_blank');
                    if (!newWin || newWin.closed || typeof newWin.closed == 'undefined') {
                        window.location.href = url;
                    } else {
                        popup.style.display = 'none';
                    }
                }
            }, 1000);
        }
    </script>
@endsection
