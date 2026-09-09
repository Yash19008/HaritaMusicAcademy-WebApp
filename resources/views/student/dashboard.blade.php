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
            border-radius: var(--radius-lg);
            padding: 1.1rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .student-card-banner {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background-color: var(--bg-card);
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(20, 85, 61, 0.05) 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.1rem;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .student-card-banner img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary);
            z-index: 2;
        }

        .student-class-box {
            background-color: var(--border-light);
            border-left: 4px solid var(--primary);
            padding: 0.85rem;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            margin-bottom: 0.75rem;
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
            border-radius: var(--radius-lg);
            padding: 1.1rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .student-card-banner {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            background-color: var(--bg-card);
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(20, 85, 61, 0.05) 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.1rem;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .student-card-banner img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary);
            z-index: 2;
        }

        .student-class-box {
            background-color: var(--border-light);
            border-left: 4px solid var(--primary);
            padding: 0.85rem;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            margin-bottom: 0.75rem;
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
    <!-- STUDENT VIEW CONTAINER -->
    <div id="studentDashboardView" data-role-limit="student" class="slide-up">

        <div class="student-dashboard-layout">
            <!-- Banner card -->
            <div class="student-card-banner">
                @if(isset(auth()->user()->avatar) && auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="{{ $student->name ?? 'Student' }} Profile">
                @else
                    <div style="width: 70px; height: 70px; border-radius: 50%; border: 2.5px solid var(--primary); z-index: 2; background: var(--bg-main, #F8F5EF); color: var(--primary); font-weight: bold; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                        {{ strtoupper(substr($student->name ?? 'S', 0, 2)) }}
                    </div>
                @endif
                <div>
                    <span class="text-muted font-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Student
                        Portal</span>
                    <h2 class="text-serif text-primary" style="font-size: 1.4rem; margin-bottom: 0.25rem;">Welcome back,
                        {{ explode(' ', $student->name ?? 'Student')[0] }}! 👋</h2>
                    <p class="text-muted mb-2" style="font-size: 0.85rem;">Keep practicing, great music accomplishments take
                        time.</p>
                    @if ($nextClass)
                        @php
                            $now = now();
                            $minutesUntilClass = $now->diffInMinutes($nextClass->starts_at, false);
                            $canJoin = $minutesUntilClass <= 15 && $now->isBefore($nextClass->ends_at ?? $nextClass->starts_at->copy()->addMinutes($nextClass->duration_minutes ?? 40));
                        @endphp
                        @if($canJoin)
                            <a href="{{ $nextClass->student_join_url }}" target="_blank" class="btn btn-primary btn-sm"
                                onclick="alert('The call is recorded for quality purposes.')">Join Next Class</a>
                        @else
                            <button class="btn btn-primary btn-sm" style="opacity: 0.5; cursor: not-allowed;" disabled title="You can join 15 minutes before the class starts.">Join Next Class</button>
                        @endif
                    @else
                        <button class="btn btn-primary btn-sm"
                            onclick="window.location.href='{{ route('student.my-classes') }}'">View Schedule</button>
                    @endif
                </div>
            </div>

            <!-- Next Class Box -->
            <div class="student-card">
                @if ($nextClass)
                    <div>
                        <span class="form-label" style="font-size: 0.7rem;">Next Class</span>
                        <h4 class="font-semibold text-primary mt-1">
                            {{ $nextClass->title ?? ($student->instrument ?? 'Music Class') }}</h4>
                        <p class="text-muted" style="font-size: 0.8rem;">with <a href="javascript:void(0)"
                                class="text-primary hover-underline font-semibold"
                                data-name="{{ $nextClass->teacher->user->name ?? 'N/A' }}"
                                data-specialization="{{ $nextClass->teacher->categories ?? 'M/A' }}"
                                data-level="{{ $nextClass->teacher->level ?? 'N/A' }}"
                                data-certifications="{{ $nextClass->teacher->certifications ?? 'N/A' }}"
                                data-bio="{{ $nextClass->teacher->bio ?? 'N/A' }}"
                                data-youtube="{{ $nextClass->teacher->youtube_url ?? 'N/A' }}"
                                data-avatar="{{ $nextClass->teacher->user->avatar ?? '' }}"
                                onclick="showTeacherProfileModal(this)">{{ $nextClass->teacher->user->name ?? 'Instructor' }}</a>
                        </p>
                    </div>
                    <div class="student-class-box mt-2">
                        <div class="font-semibold" style="font-size: 0.85rem;">
                            {{ \Carbon\Carbon::parse($nextClass->starts_at)->format('l, h:i A') }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Duration:
                            {{ \Carbon\Carbon::parse($nextClass->ends_at)->diffInMinutes($nextClass->starts_at) }} mins
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm"
                        onclick="window.location.href='{{ route('student.my-classes') }}'">Reschedule</button>
                @else
                    <div>
                        <span class="form-label" style="font-size: 0.7rem;">Next Class</span>
                        <h4 class="font-semibold text-primary mt-1">No Upcoming Classes</h4>
                        <p class="text-muted" style="font-size: 0.8rem;">You have no classes scheduled.</p>
                    </div>
                @endif
            </div>

            <!-- Progress Ring Card -->
            <div class="student-card align-center text-center">
                <span class="form-label">Progress Overview</span>
                <div class="text-muted mb-2" style="font-size: 0.75rem;">Keep going, you're doing amazing!</div>

                <div id="studentProgressRing" class="progress-ring-container my-2"></div>

                <div class="w-100 mt-2" style="font-size: 0.8rem; text-align: left;">
                    <div class="d-flex justify-between border-bottom p-1">
                        <span>Classes Completed</span>
                        <span class="font-bold">{{ $completedClassesCount }} / {{ $totalClassesCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Log -->
        <div class="card">
            <div class="card-header">
                <h4 class="font-semibold">Credit Transaction Log</h4>
            </div>
            <div class="card-body p-3">
                <table class="table display responsive nowrap" id="transactionsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Student Name</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>Reason / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $txn)
                            <tr>
                                <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                                <td class="font-semibold">{{ $student->name ?? 'Student' }}</td>
                                <td
                                    class="font-bold {{ in_array(strtolower($txn->action), ['added', 'add']) ? 'text-success' : 'text-danger' }}">
                                    {{ in_array(strtolower($txn->action), ['added', 'add']) ? '+' : '-' }}{{ abs($txn->quantity) }}
                                    Credits
                                </td>
                                <td>{{ abs($txn->quantity) }}</td>
                                <td>{{ $txn->reason }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    {{-- ── Teacher Bio Modal ── --}}
    <div id="teacherProfileModal" class="modal-backdrop">
        <div class="modal" style="max-width: 480px;">
            <div class="modal-header">
                <h3 class="font-semibold text-serif">Mentor Biography</h3>
                <button class="modal-close" onclick="closeTeacherProfileModal()">×</button>
            </div>
            <div class="modal-body p-4" id="teacherProfileModalBody">
                <!-- Dynamically populated by JS -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 5,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search logs..."
                }
            });

            // Draw simple progress ring
            const percentage =
                {{ $totalClassesCount > 0 ? round(($completedClassesCount / $totalClassesCount) * 100) : 0 }};
            const container = document.getElementById('studentProgressRing');
            if (container) {
                container.innerHTML = `
            <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e2e8f0" stroke-width="10" />
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="10" 
                            stroke-dasharray="314.159" stroke-dashoffset="${314.159 - (314.159 * percentage) / 100}" 
                            stroke-linecap="round" transform="rotate(-90 60 60)" />
                </svg>
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <span style="font-size: 1.5rem; font-weight: bold; color: #10b981;">${percentage}%</span>
                </div>
            </div>
        `;
            }
        });

        // Teacher bio modal
        function showTeacherProfileModal(el) {
            const modal = document.getElementById("teacherProfileModal");
            const body = document.getElementById("teacherProfileModalBody");
            if (!modal || !body) return;

            const name = el.getAttribute('data-name');
            const specialization = el.getAttribute('data-specialization');
            const level = el.getAttribute('data-level');
            const certifications = el.getAttribute('data-certifications');
            const bio = el.getAttribute('data-bio');
            const youtube = el.getAttribute('data-youtube');
            const avatarUrl = el.getAttribute('data-avatar');

            // Helper function to extract embed URL
            const getEmbedUrl = (url) => {
                if (!url) return null;
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = url.match(regExp);
                if (match && match[2].length === 11) {
                    return "https://www.youtube.com/embed/" + match[2];
                }
                return null;
            };

            const embedUrl = getEmbedUrl(youtube);
            let youtubeHtml = "";
            if (embedUrl) {
                youtubeHtml = `
                    <div class="mt-3" style="border-top: 1px solid var(--border-light); padding-top: 0.75rem;">
                        <h4 class="font-bold" style="font-size: 0.85rem; margin-bottom: 0.4rem; color: var(--primary);">Featured Performance</h4>
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <iframe src="${embedUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                `;
            } else {
                youtubeHtml = `
                    <div class="mt-3 p-3 text-center text-muted" style="border: 1px dashed var(--border-color); border-radius: var(--radius-md); font-size: 0.8rem; background: var(--bg-body);">
                        🎥 No featured performance video uploaded yet.
                    </div>
                `;
            }

            const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

            let avatarHtml = '';
            if (avatarUrl && avatarUrl !== '') {
                avatarHtml = `<img src="${avatarUrl}" alt="${name}" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin: 0 auto 0.5rem; border: 2.5px solid var(--primary); display: block;">`;
            } else {
                avatarHtml = `
                    <div class="avatar avatar-lg mx-auto" style="width: 70px; height: 70px; font-size: 1.5rem; line-height: 70px; border-radius: 50%; background: var(--primary-light); color: #fff; font-weight: bold; margin-bottom: 0.5rem; border: 2.5px solid var(--primary); display: flex; align-items: center; justify-content: center; margin-left: auto !important; margin-right: auto !important; float: none !important;">
                        ${initials}
                    </div>
                `;
            }

            body.innerHTML = `
                <div class="text-center mb-3">
                    ${avatarHtml}
                    <h3 class="font-bold text-serif" style="font-size: 1.35rem; margin-bottom: 0.25rem;">${name}</h3>
                    <span class="badge badge-success" style="font-size: 0.75rem;">Academy Mentor</span>
                </div>
                <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
                    <span class="text-muted">Specialization</span>
                    <span class="font-bold">${specialization}</span>
                </div>
                <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
                    <span class="text-muted">Expertise Level</span>
                    <span class="font-bold">${level}</span>
                </div>
                <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
                    <span class="text-muted">Certifications</span>
                    <span class="font-semibold">${certifications}</span>
                </div>
                <div class="mt-3" style="font-size: 0.82rem; line-height: 1.5; color: var(--text-muted); text-align: justify; border-top: 1px solid var(--border-light); padding-top: 0.75rem;">
                    <b>Biography:</b> ${bio}
                </div>
                ${youtubeHtml}
                <button class="btn btn-secondary w-100 mt-4" onclick="closeTeacherProfileModal()">Close Bio</button>
            `;

            modal.classList.add('show');
        }

        function closeTeacherProfileModal() {
            document.getElementById('teacherProfileModal').classList.remove('show');
        }
        
        // Close modal when clicking backdrop
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('teacherProfileModal');
            if (e.target === modal) {
                closeTeacherProfileModal();
            }
        });
    </script>
@endpush

@if($student && !$student->intro_video_path)
{{-- ═══════════════ INTRO VIDEO UPLOAD POPUP ═══════════════ --}}
@push('styles')
<style>
    /* ══════════════════════════════════════════════════
       INTRO VIDEO POPUP — Harita Theme
       Primary: #51040e (deep maroon)
       Secondary: #d4af37 (gold)
       BG: #F8F5EF (warm cream)
       Font: Poppins
    ══════════════════════════════════════════════════ */

    #introVideoOverlay {
        position: fixed;
        inset: 0;
        background: rgba(30, 8, 8, 0.82);
        backdrop-filter: blur(14px) saturate(140%);
        -webkit-backdrop-filter: blur(14px) saturate(140%);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: ivOverlayIn 0.3s ease forwards;
        font-family: 'Poppins', system-ui, sans-serif;
    }

    @keyframes ivOverlayIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .iv-card {
        background: #fff;
        border: 1px solid rgba(81, 4, 14, 0.12);
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        box-shadow:
            0 2px 0 0 #d4af37,
            0 24px 60px rgba(30, 8, 8, 0.35),
            0 0 0 1px rgba(81, 4, 14, 0.06);
        overflow: hidden;
        animation: ivCardIn 0.48s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        position: relative;
    }

    @keyframes ivCardIn {
        from { transform: translateY(40px) scale(0.96); opacity: 0; }
        to   { transform: translateY(0) scale(1); opacity: 1; }
    }

    /* ── Header band ── */
    .iv-top-band {
        background: linear-gradient(135deg, #51040e 0%, #320208 60%, #3d0209 100%);
        padding: 2rem 2rem 1.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Subtle noise texture on header */
    .iv-top-band::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 20%, rgba(212,175,55,0.18) 0%, transparent 60%),
                    radial-gradient(ellipse at 10% 80%, rgba(212,175,55,0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .iv-top-band > * { position: relative; z-index: 1; }

    .iv-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(212, 175, 55, 0.15);
        border: 1px solid rgba(212, 175, 55, 0.4);
        border-radius: 999px;
        padding: 0.28rem 0.85rem;
        font-size: 0.68rem;
        font-weight: 700;
        color: #f8efd0;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 1.1rem;
    }

    .iv-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #d4af37;
        box-shadow: 0 0 6px #d4af37;
        animation: ivDotPulse 1.8s ease-in-out infinite;
        flex-shrink: 0;
    }

    @keyframes ivDotPulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }

    .iv-icon-wrap {
        width: 76px;
        height: 76px;
        border-radius: 18px;
        background: linear-gradient(145deg, #d4af37, #b38b22);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        box-shadow: 0 6px 24px rgba(180, 130, 20, 0.5), inset 0 1px 0 rgba(255,255,255,0.2);
        animation: ivIconFloat 3.5s ease-in-out infinite;
    }

    @keyframes ivIconFloat {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-5px); }
    }

    .iv-icon-wrap svg {
        width: 36px;
        height: 36px;
        color: #51040e;
        filter: drop-shadow(0 1px 3px rgba(0,0,0,0.2));
    }

    .iv-heading {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 0.5rem;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .iv-heading span {
        color: #f8efd0;
        font-style: italic;
    }

    .iv-subheading {
        font-size: 0.8rem;
        color: rgba(248, 239, 208, 0.7);
        line-height: 1.65;
        max-width: 340px;
        margin: 0 auto;
    }

    /* ── Body (cream/white bg) ── */
    .iv-body {
        padding: 1.5rem;
        background: #F8F5EF;
    }

    /* Drop Zone */
    .iv-drop-zone {
        border: 1.5px dashed rgba(81, 4, 14, 0.3);
        border-radius: 14px;
        padding: 1.75rem 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: rgba(81, 4, 14, 0.03);
        position: relative;
        overflow: hidden;
    }

    .iv-drop-zone:hover,
    .iv-drop-zone.drag-over {
        border-color: #51040e;
        background: rgba(81, 4, 14, 0.06);
        transform: scale(1.01);
    }

    .iv-drop-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .iv-drop-icon-svg {
        width: 44px;
        height: 44px;
        margin: 0 auto 0.7rem;
        color: #51040e;
        display: block;
        opacity: 0.8;
    }

    .iv-drop-title {
        color: #1e293b;
        font-weight: 700;
        font-size: 0.88rem;
        margin-bottom: 0.25rem;
        letter-spacing: -0.01em;
    }

    .iv-drop-sub {
        color: #94a3b8;
        font-size: 0.74rem;
    }

    /* Format chips */
    .iv-format-chips {
        display: flex;
        justify-content: center;
        gap: 0.35rem;
        margin-top: 0.85rem;
        flex-wrap: wrap;
    }

    .iv-chip {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.18rem 0.5rem;
        border-radius: 5px;
        background: rgba(81, 4, 14, 0.07);
        border: 1px solid rgba(81, 4, 14, 0.15);
        color: #7a1c2b;
        letter-spacing: 0.05em;
    }

    /* File selected state */
    .iv-file-info {
        display: none;
        align-items: center;
        gap: 0.85rem;
        background: rgba(13, 148, 136, 0.07);
        border: 1px solid rgba(13, 148, 136, 0.22);
        border-radius: 11px;
        padding: 0.8rem 1rem;
        margin-top: 0.85rem;
    }

    .iv-file-info-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(13, 148, 136, 0.12);
        border: 1px solid rgba(13, 148, 136, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #0d9488;
    }

    .iv-file-info-icon svg { width: 17px; height: 17px; }
    .iv-file-info-text { flex: 1; min-width: 0; }

    .iv-file-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0d9488;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .iv-file-size {
        font-size: 0.68rem;
        color: rgba(13, 148, 136, 0.6);
        margin-top: 0.1rem;
    }

    /* Progress */
    .iv-progress-wrap {
        display: none;
        margin-top: 0.85rem;
        background: #fff;
        border: 1px solid rgba(81, 4, 14, 0.1);
        border-radius: 11px;
        padding: 0.85rem 1rem;
    }

    .iv-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.55rem;
    }

    .iv-progress-title {
        font-size: 0.73rem;
        font-weight: 600;
        color: #475569;
    }

    .iv-progress-pct {
        font-size: 0.73rem;
        font-weight: 800;
        color: #51040e;
        font-variant-numeric: tabular-nums;
    }

    .iv-progress-track {
        height: 5px;
        border-radius: 99px;
        background: rgba(81, 4, 14, 0.08);
        overflow: hidden;
    }

    .iv-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(90deg, #51040e, #7a1c2b, #d4af37);
        background-size: 200% 100%;
        width: 0%;
        transition: width 0.25s ease;
        animation: ivProgressShimmer 2s linear infinite;
    }

    @keyframes ivProgressShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Divider */
    .iv-divider {
        height: 1px;
        background: rgba(81, 4, 14, 0.08);
        margin: 1.1rem 0 1rem;
    }

    /* Actions */
    .iv-actions {
        display: flex;
        gap: 0.6rem;
    }

    .iv-btn-skip {
        padding: 0.7rem 1rem;
        background: #fff;
        color: #475569;
        border: 1px solid rgba(81, 4, 14, 0.15);
        border-radius: 11px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
    }

    .iv-btn-skip:hover {
        background: #F8F5EF;
        color: #1e293b;
        border-color: rgba(81, 4, 14, 0.3);
    }

    .iv-btn-upload {
        flex: 1;
        padding: 0.72rem 1.25rem;
        background: linear-gradient(135deg, #51040e 0%, #320208 100%);
        color: #fff;
        border: none;
        border-radius: 11px;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        letter-spacing: -0.01em;
        box-shadow: 0 4px 16px rgba(81, 4, 14, 0.35), inset 0 1px 0 rgba(255,255,255,0.08);
        font-family: 'Poppins', sans-serif;
        position: relative;
        overflow: hidden;
    }

    .iv-btn-upload::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 60px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.25), transparent);
        transition: left 0.5s ease;
    }

    .iv-btn-upload:hover::after { left: 200%; }

    .iv-btn-upload svg { width: 15px; height: 15px; flex-shrink: 0; }

    .iv-btn-upload:hover:not(:disabled) {
        background: linear-gradient(135deg, #3d020a 0%, #220105 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(81, 4, 14, 0.45), inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .iv-btn-upload:active:not(:disabled) { transform: translateY(0); }

    .iv-btn-upload:disabled {
        background: #c8c0c0;
        color: rgba(255,255,255,0.5);
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .iv-btn-upload:disabled::after { display: none; }

    /* Footer note */
    .iv-footer-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 1rem;
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .iv-footer-note svg { width: 11px; height: 11px; flex-shrink: 0; color: #d4af37; }
</style>
@endpush

<div id="introVideoOverlay">
    <div class="iv-card">

        {{-- ── Header ── --}}
        <div class="iv-top-band">
            <div class="iv-badge-tag">
                <span class="iv-badge-dot"></span>
                First Login
            </div>

            <div class="iv-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 10l4.553-2.069A1 1 0 0 1 21 8.882v6.236a1 1 0 0 1-1.447.894L15 14"/>
                    <rect x="3" y="7" width="12" height="10" rx="2"/>
                </svg>
            </div>

            <h2 class="iv-heading">Share Your <span>Musical Journey</span></h2>
            <p class="iv-subheading">Upload a short intro video of yourself playing your instrument. It helps us understand your current level and personalise your learning path.</p>
        </div>

        {{-- ── Body ── --}}
        <div class="iv-body">
            <form id="introVideoForm" method="POST" action="{{ route('student.intro-video.upload') }}" enctype="multipart/form-data">
                @csrf

                {{-- Drop Zone --}}
                <div class="iv-drop-zone" id="ivDropZone">
                    <input type="file" name="intro_video" id="introVideoInput" accept="video/mp4,video/quicktime,video/avi,video/webm">
                    <svg class="iv-drop-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <div class="iv-drop-title">Drop your video here or click to browse</div>
                    <div class="iv-drop-sub">Select a video file from your device</div>
                    <div class="iv-format-chips">
                        <span class="iv-chip">MP4</span>
                        <span class="iv-chip">MOV</span>
                        <span class="iv-chip">AVI</span>
                        <span class="iv-chip">WEBM</span>
                        <span class="iv-chip">MAX 100 MB</span>
                    </div>
                </div>

                {{-- File info --}}
                <div class="iv-file-info" id="ivFileSelected">
                    <div class="iv-file-info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="iv-file-info-text">
                        <div class="iv-file-name" id="ivFileName">Ready to upload</div>
                        <div class="iv-file-size" id="ivFileSize"></div>
                    </div>
                </div>

                {{-- Progress --}}
                <div class="iv-progress-wrap" id="ivProgressWrap">
                    <div class="iv-progress-header">
                        <span class="iv-progress-title">Uploading video…</span>
                        <span class="iv-progress-pct" id="ivProgressLabel">0%</span>
                    </div>
                    <div class="iv-progress-track">
                        <div class="iv-progress-fill" id="ivProgressFill"></div>
                    </div>
                </div>

                <div class="iv-divider"></div>

                {{-- Buttons --}}
                <div class="iv-actions">
                    <button type="button" class="iv-btn-skip" onclick="document.getElementById('introVideoOverlay').style.display='none'">
                        Skip for now
                    </button>
                    <button type="submit" class="iv-btn-upload" id="ivSubmitBtn" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Upload Intro Video
                    </button>
                </div>

                {{-- Footer --}}
                <div class="iv-footer-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Visible only to your mentor &amp; academy admin
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input     = document.getElementById('introVideoInput');
        const dropZone  = document.getElementById('ivDropZone');
        const fileInfo  = document.getElementById('ivFileSelected');
        const fileName  = document.getElementById('ivFileName');
        const fileSize  = document.getElementById('ivFileSize');
        const submitBtn = document.getElementById('ivSubmitBtn');
        const form      = document.getElementById('introVideoForm');
        const progressW = document.getElementById('ivProgressWrap');
        const progressF = document.getElementById('ivProgressFill');
        const progressL = document.getElementById('ivProgressLabel');

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) handleFile(this.files[0]);
        });

        dropZone.addEventListener('dragover',  (e) => { e.preventDefault(); dropZone.classList.add('drag-over'); });
        dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('drag-over'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const dT = new DataTransfer();
                dT.items.add(e.dataTransfer.files[0]);
                input.files = dT.files;
                handleFile(e.dataTransfer.files[0]);
            }
        });

        function handleFile(file) {
            const validExts  = /\.(mp4|mov|avi|webm)$/i;
            const validTypes = ['video/mp4','video/quicktime','video/avi','video/webm','video/x-msvideo'];
            if (!validTypes.includes(file.type) && !validExts.test(file.name)) {
                alert('Please select a valid video file (MP4, MOV, AVI, or WebM).');
                return;
            }
            if (file.size > 102400 * 1024) {
                alert('File too large. Maximum size is 100 MB.');
                return;
            }
            const sizeMB   = (file.size / (1024 * 1024)).toFixed(1);
            const shortName = file.name.length > 38 ? file.name.substring(0, 35) + '…' : file.name;
            fileName.textContent = shortName;
            fileSize.textContent = sizeMB + ' MB · Ready to upload';
            fileInfo.style.display = 'flex';
            submitBtn.disabled = false;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!input.files || !input.files[0]) return;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;animation:ivSpin 1s linear infinite;">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                Uploading…`;

            progressW.style.display = 'block';
            fileInfo.style.display  = 'none';

            const fd  = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (ev) => {
                if (ev.lengthComputable) {
                    const pct = Math.round((ev.loaded / ev.total) * 100);
                    progressF.style.width  = pct + '%';
                    progressL.textContent  = pct + '%';
                }
            });

            xhr.addEventListener('load', () => {
                progressF.style.width = '100%';
                progressL.textContent = '100%';
                submitBtn.innerHTML   = '✓ Done! Refreshing…';
                setTimeout(() => window.location.reload(), 700);
            });

            xhr.addEventListener('error', () => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Upload Intro Video`;
                progressW.style.display = 'none';
                fileInfo.style.display  = 'flex';
                alert('Upload failed. Please try again.');
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(fd);
        });
    })();
</script>
<style>
    @keyframes ivSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
</style>
@endpush
@endif
