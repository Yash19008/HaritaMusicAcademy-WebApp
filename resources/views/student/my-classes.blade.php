@extends('layouts.main')
@section('title', 'My Classes')
@section('page', 'my-classes')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* ── DataTable theme overrides ── */
        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1,
        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1,
        table.dataTable.display tbody tr:hover>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_1 {
            background: transparent !important;
        }

        table.dataTable.display tbody tr>td {
            background: transparent !important;
        }

        table.dataTable thead th {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color) !important;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        table.dataTable tbody td {
            padding: 0.9rem 1rem;
            font-size: 0.85rem;
            color: var(--text-main);
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
        }

        table.dataTable tbody tr:hover {
            background: var(--bg-main);
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 0.35rem 0.65rem;
            font-size: 0.82rem;
            font-family: var(--font-main);
            color: var(--text-main);
            background: var(--bg-card);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-family: var(--font-main);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 0.8rem;
            border-radius: var(--radius-sm) !important;
            padding: 0.3rem 0.7rem !important;
            font-family: var(--font-main);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--bg-main) !important;
            border-color: var(--border-color) !important;
            color: var(--primary) !important;
        }

        /* ── Status badges ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .status-badge.scheduled {
            background: rgba(81, 4, 14, 0.08);
            color: #51040e;
            border: 1px solid rgba(81, 4, 14, 0.18);
        }

        .status-badge.completed {
            background: rgba(13, 148, 136, 0.09);
            color: #0d9488;
            border: 1px solid rgba(13, 148, 136, 0.2);
        }

        .status-badge.rescheduled {
            background: rgba(212, 175, 55, 0.12);
            color: #b38b22;
            border: 1px solid rgba(212, 175, 55, 0.25);
        }

        .status-badge.cancelled {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.18);
        }

        /* ── Action buttons ── */
        .btn-join {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.38rem 0.9rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: var(--font-main);
        }

        .btn-join:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-reschedule {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.38rem 0.9rem;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font-main);
        }

        .btn-reschedule:hover {
            border-color: var(--secondary);
            color: var(--secondary-dark);
            background: rgba(212, 175, 55, 0.05);
        }

        /* ── Instrument cell ── */
        .instrument-cell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .instrument-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(81, 4, 14, 0.08);
            border: 1px solid rgba(81, 4, 14, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        /* ── Date cell ── */
        .date-cell {
            line-height: 1.3;
        }

        .date-cell .date-day {
            font-weight: 700;
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .date-cell .date-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.1rem;
        }

        /* ── Teacher link ── */
        .teacher-link {
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.83rem;
            transition: color 0.15s;
        }

        .teacher-link:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(81, 4, 14, 0.06);
            border: 1px solid rgba(81, 4, 14, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary);
        }

        .empty-state-icon svg {
            width: 28px;
            height: 28px;
        }

        .empty-state h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.35rem;
        }

        .empty-state p {
            font-size: 0.82rem;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header / Filters ── --}}
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0;">My Classes</h2>
                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.2rem 0 0;">
                    Showing all your scheduled, completed and rescheduled sessions.
                </p>
            </div>
            <div class="d-flex gap-2 flex-nowrap align-center" style="margin-left: auto;">
                <form method="GET" class="filter-container d-flex gap-2 flex-nowrap align-center mb-0">
                    <div class="filter-input-group"
                        style="display: flex; align-items: center; border: 1px solid var(--border-light); border-radius: 6px; padding: 0 0.3rem; background: var(--bg-main); height: 32px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)"
                            stroke-width="2.5">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <input type="date" name="start_date" class="form-control"
                            style="border:none; box-shadow:none; padding: 0 0.3rem; font-size: 0.78rem; background:transparent; height: 100%; min-width: 110px;"
                            value="{{ request('start_date', today()->format('Y-m-d')) }}" title="Start Date">
                    </div>
                    <span class="text-muted" style="font-size: 0.75rem; font-weight: 500;">&rarr;</span>
                    <div class="filter-input-group"
                        style="display: flex; align-items: center; border: 1px solid var(--border-light); border-radius: 6px; padding: 0 0.3rem; background: var(--bg-main); height: 32px;">
                        <input type="date" name="end_date" class="form-control"
                            style="border:none; box-shadow:none; padding: 0 0.3rem; font-size: 0.78rem; background:transparent; height: 100%; min-width: 110px;"
                            value="{{ request('end_date', today()->format('Y-m-d')) }}" title="End Date">
                    </div>
                    <button type="submit" class="btn btn-primary filter-btn"
                        style="height: 32px; padding: 0 0.6rem; border-radius: 6px; font-size: 0.78rem; display: flex; align-items: center; gap: 0.3rem;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Filter
                    </button>
                </form>
                {{-- Status filter synced to DataTables --}}
                <select id="statusFilter" class="form-control"
                    style="min-width: 130px; font-size: 0.78rem; height: 32px; padding: 0 0.4rem; display: inline-block; width: auto;"
                    onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="reschedule_requested">Reschedule Requested</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ── Classes Table ── --}}
    <div class="card">
        <div class="card-body" style="padding: 0 0 1rem;">
            @if ($bookings->count() > 0)
                <div style="overflow-x: auto;">
                    <table id="classesTable" class="display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Time</th>
                                <th>Instrument</th>
                                <th>Mentor</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Attendance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $i => $booking)
                                @php
                                    $status = $booking->status;
                                    $badgeClass = match ($status) {
                                        'completed' => 'completed',
                                        'reschedule_requested' => 'rescheduled',
                                        'cancelled' => 'cancelled',
                                        default => 'scheduled',
                                    };
                                    $statusLabel = match ($status) {
                                        'reschedule_requested' => 'Rescheduled',
                                        default => ucfirst($status),
                                    };
                                @endphp
                                <tr data-status="{{ $status }}">
                                    {{-- # --}}
                                    <td style="color: var(--text-muted); font-size:0.78rem; font-weight:600;">
                                        {{ $i + 1 }}
                                    </td>

                                    {{-- Date & Time --}}
                                    <td data-order="{{ $booking->starts_at->timestamp }}">
                                        <div class="date-cell">
                                            <div class="date-day">{{ $booking->starts_at->format('d M Y') }}</div>
                                            <div class="date-time">{{ $booking->starts_at->format('h:i A') }}</div>
                                        </div>
                                    </td>

                                    {{-- Instrument --}}
                                    <td>
                                        <div class="instrument-cell">
                                            <div class="instrument-icon">🎵</div>
                                            <span
                                                style="font-weight: 600; color: var(--text-main);">{{ $booking->instrument }}</span>
                                        </div>
                                    </td>

                                    {{-- Mentor --}}
                                    <td>
                                        <span class="teacher-link" data-name="{{ $booking->teacher->user->name ?? 'N/A' }}"
                                            data-specialization="{{ $booking->teacher->categories ?? 'M/A' }}"
                                            data-level="{{ $booking->teacher->level ?? 'N/A' }}"
                                            data-certifications="{{ $booking->teacher->certifications ?? 'N/A' }}"
                                            data-bio="{{ $booking->teacher->bio ?? 'N/A' }}"
                                            data-youtube="{{ $booking->teacher->youtube_url ?? 'N/A' }}"
                                            data-avatar="{{ $booking->teacher->user->avatar ?? '' }}"
                                            onclick="showTeacherProfileModal(this)">
                                            {{ $booking->teacher->user->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Duration --}}
                                    <td>
                                        <span style="font-weight: 600;">{{ $booking->duration_minutes }}</span>
                                        <span style="color: var(--text-muted); font-size: 0.78rem;"> min</span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <span class="status-badge {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Attendance --}}
                                    <td>
                                        @if ($status === 'completed')
                                            @if ($booking->student_attended === 1)
                                                <span class="badge badge-success" style="font-size:0.75rem;">Present</span>
                                            @elseif($booking->student_attended === 0)
                                                <span class="badge badge-danger" style="font-size:0.75rem;">Absent</span>
                                            @else
                                                <span class="text-muted" style="font-size:0.75rem;">Pending</span>
                                            @endif
                                        @else
                                            <span class="text-muted" style="font-size:0.75rem;">—</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        @if ($status === 'scheduled')
                                            <div class="d-flex gap-2">
                                                @php
                                                    $now = now();
                                                    $minutesUntilClass = $now->diffInMinutes(
                                                        $booking->starts_at,
                                                        false,
                                                    );
                                                    $canJoin =
                                                        $minutesUntilClass <= 15 &&
                                                        $now->isBefore(
                                                            $booking->ends_at ??
                                                                $booking->starts_at
                                                                    ->copy()
                                                                    ->addMinutes($booking->duration_minutes ?? 40),
                                                        );
                                                @endphp
                                                @if ($canJoin)
                                                    <a href="{{ $booking->student_join_url }}" target="_blank"
                                                        class="btn-join"
                                                        onclick="alert('The call is recorded for quality purposes.')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                            <rect x="1" y="5" width="15" height="14"
                                                                rx="2" ry="2"></rect>
                                                        </svg>
                                                        Join
                                                    </a>
                                                @else
                                                    <button class="btn-join" style="opacity: 0.5; cursor: not-allowed;"
                                                        title="You can join 15 minutes before the class starts." disabled>
                                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                            <rect x="1" y="5" width="15" height="14"
                                                                rx="2" ry="2"></rect>
                                                        </svg>
                                                        Join
                                                    </button>
                                                @endif
                                                @php
                                                    $isLocked = now()
                                                        ->addHours($lockHours)
                                                        ->greaterThan($booking->starts_at);
                                                    $limitReached = $reschedulesThisMonth >= 2;
                                                    $canReschedule = !$isLocked && !$limitReached;
                                                @endphp
                                                @if ($canReschedule)
                                                    <button class="btn-reschedule"
                                                        onclick="openRescheduleModal({{ $booking->id }}, {{ $booking->teacher_id }})">
                                                        <svg width="11" height="11" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M23 4v6h-6" />
                                                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                                                        </svg>
                                                        Reschedule
                                                    </button>
                                                @else
                                                    <button class="btn-reschedule"
                                                        style="opacity: 0.5; cursor: not-allowed; background: #e2e8f0; color: #64748b;"
                                                        title="{{ $limitReached ? 'Limit of 2 reschedules per month reached' : 'Cannot reschedule within ' . $lockHours . ' hours of class' }}"
                                                        disabled>
                                                        <svg width="11" height="11" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M23 4v6h-6" />
                                                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                                                        </svg>
                                                        Reschedule
                                                    </button>
                                                @endif
                                            </div>
                                        @elseif($status === 'reschedule_requested')
                                            <span
                                                style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">Awaiting
                                                approval…</span>
                                        @else
                                            <span style="color: var(--text-light); font-size: 0.78rem;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <h4>No classes yet</h4>
                    <p>Your upcoming and past sessions will appear here once the admin schedules a class for you.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Reschedule Modal ── --}}
    <div id="rescheduleModal" class="modal-backdrop">
        <div class="modal" style="max-width: 550px;">
            <div class="modal-header">
                <h3 class="font-semibold">Request Reschedule</h3>
                <button class="modal-close" onclick="closeRescheduleModal()">×</button>
            </div>
            <form id="rescheduleForm" method="POST" action=""
                onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
                @csrf
                <div class="modal-body">
                    <div
                        style="border: 1px solid #51040e; border-left: 4px solid #51040e; background-color: rgba(81,4,14,0.04); color: #51040e; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.8rem;">
                        <h4 class="font-bold"
                            style="margin-bottom: 0.4rem; color: #51040e; display: flex; align-items: center; gap: 0.35rem;">
                            <span>⚠️</span> Rescheduling Policy
                        </h4>
                        <ul style="padding-left: 1.2rem; margin: 0; list-style-type: disc;">
                            <li style="margin-bottom: 0.25rem;">National Students can reschedule a class up to 10 hours
                                before the scheduled class.</li>
                            <li style="margin-bottom: 0.25rem;">International Students can reschedule a class up to 12
                                hours before the scheduled class.</li>
                            <li style="margin-bottom: 0.25rem;">Maximum 2 reschedules per calendar month are allowed.</li>
                            <li>Requests made after the permitted time will be automatically rejected.</li>
                        </ul>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="rescheduleTeacher">Teacher</label>
                        <select id="rescheduleTeacher" name="teacher_id" class="form-control" required
                            onchange="fetchRescheduleSlots()">
                            <option value="">Select Teacher</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="rescheduleDate">Proposed Date</label>
                        <input type="date" id="rescheduleDate" name="date" class="form-control" required
                            onchange="fetchRescheduleSlots()">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="rescheduleTime">Preferred Time Slot</label>
                        <select id="rescheduleTime" name="time_slot" class="form-control" required disabled>
                            <option value="">Select a date and teacher first</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rescheduleReason">Reason for Reschedule</label>
                        <textarea id="rescheduleReason" name="reschedule_reason" class="form-control" rows="3"
                            placeholder="Explain the reason for your request…" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
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
    {{-- jQuery + DataTables CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        let dtClasses;

        document.addEventListener('DOMContentLoaded', function() {
            @if ($bookings->count() > 0)
                dtClasses = $('#classesTable').DataTable({
                    responsive: true,
                    pageLength: 15,
                    lengthMenu: [10, 15, 25, 50],
                    order: [
                        [1, 'asc']
                    ], // Sort by date ascending
                    dom: 'lrtip', // Hide default search (we use filter above)
                    columnDefs: [{
                            orderable: false,
                            targets: [2, 6, 7]
                        }, // Instrument, Attendance & Actions not sortable
                        {
                            width: '40px',
                            targets: 0
                        },
                    ],
                    language: {
                        emptyTable: 'No classes found.',
                        zeroRecords: 'No matching classes found.',
                        info: 'Showing _START_ to _END_ of _TOTAL_ classes',
                        infoEmpty: 'No classes available',
                        lengthMenu: 'Show _MENU_ per page',
                        paginate: {
                            previous: '‹',
                            next: '›'
                        },
                    }
                });
            @endif
        });

        // Status filter
        function filterTable() {
            const val = document.getElementById('statusFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#classesTable tbody tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                row.style.display = (!val || status === val) ? '' : 'none';
            });
        }

        // Reschedule modal
        function openRescheduleModal(id, currentTeacherId) {
            document.getElementById('rescheduleForm').action = `/student/my-classes/${id}/reschedule`;
            document.getElementById('rescheduleTeacher').value = currentTeacherId;
            document.getElementById('rescheduleDate').value = '';
            document.getElementById('rescheduleTime').innerHTML = '<option value="">Select a date first</option>';
            document.getElementById('rescheduleTime').disabled = true;
            document.getElementById('rescheduleModal').classList.add('show');
        }

        function fetchRescheduleSlots() {
            const teacherId = document.getElementById('rescheduleTeacher').value;
            const date = document.getElementById('rescheduleDate').value;
            const timeSelect = document.getElementById('rescheduleTime');

            if (!teacherId || !date) {
                timeSelect.innerHTML = '<option value="">Select a date and teacher first</option>';
                timeSelect.disabled = true;
                return;
            }

            timeSelect.innerHTML = '<option value="">Loading slots...</option>';
            timeSelect.disabled = true;

            fetch(`/student/reschedule/slots?teacher_id=${teacherId}&date=${date}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(slots => {
                    if (!Array.isArray(slots) || slots.length === 0) {
                        timeSelect.innerHTML = '<option value="">No available slots on this date</option>';
                        return;
                    }
                    timeSelect.innerHTML = '<option value="">— Select a time slot —</option>';
                    slots.forEach(slot => {
                        const label = slot.display_time;
                        const opt = document.createElement('option');
                        opt.value = label;
                        opt.textContent = label;
                        timeSelect.appendChild(opt);
                    });
                    timeSelect.disabled = false;
                })
                .catch(() => {
                    timeSelect.innerHTML = '<option value="">Error loading slots — try again</option>';
                });
        }

        function closeRescheduleModal() {
            document.getElementById('rescheduleModal').classList.remove('show');
        }

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
                avatarHtml =
                    `<img src="${avatarUrl}" alt="${name}" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin: 0 auto 0.5rem; border: 2.5px solid var(--primary); display: block;">`;
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
                    <span class="font-semibold">${specialization}</span>
                </div>
                <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
                    <span class="text-muted">Expertise Level</span>
                    <span class="font-semibold">${level}</span>
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

        // Close modals when clicking backdrop
        window.addEventListener('click', (e) => {
            const res = document.getElementById('rescheduleModal');
            const teach = document.getElementById('teacherProfileModal');
            if (e.target === res) closeRescheduleModal();
            if (e.target === teach) closeTeacherProfileModal();
        });
    </script>
@endpush
