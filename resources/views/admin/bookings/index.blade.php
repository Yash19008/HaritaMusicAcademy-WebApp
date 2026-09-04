@extends('layouts.main')
@section('title', 'Class Booking')
@section('page', 'class-booking')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* CUSTOM REDESIGN STYLES FOR BOOKING PANEL */
        .booking-container {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .booking-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        .booking-form-section {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Student Info Card styling */
        .student-info-card {
            background: var(--bg-main);
            border-left: 4px solid var(--secondary);
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .student-info-item {
            display: flex;
            flex-direction: column;
        }

        .student-info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .student-info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 0.15rem;
        }

        /* Segmented Control for Recurrence */
        .segmented-control {
            display: flex;
            background: var(--border-color);
            padding: 4px;
            border-radius: 10px;
            width: 100%;
        }

        .segmented-control-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all var(--transition-speed);
            color: var(--text-muted);
            text-align: center;
        }

        .segmented-control-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(81, 4, 14, 0.15);
        }

        /* Form layout grid adjustments */
        .booking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        /* Live Preview Card styling */
        .preview-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            height: fit-content;
            position: sticky;
            top: 1rem;
        }

        .preview-title {
            font-size: 1.1rem;
            color: var(--primary);
            font-weight: 700;
            border-bottom: 2px dashed var(--border-color);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-body {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 0.85rem;
        }

        .preview-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .preview-val {
            font-weight: 600;
            color: var(--text-main);
            text-align: right;
        }

        .preview-val.credits-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }

        .preview-val.meet-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }

        .preview-occurrences-list {
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            background: var(--border-light);
        }

        .preview-occurrence-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            border-radius: 4px;
            background: #ffffff;
            border-left: 3px solid var(--primary);
        }

        .preview-occurrence-date {
            font-weight: 500;
        }

        .preview-occurrence-index {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Meet Button in Active Classes Table */
        .meet-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #0d9488;
            color: white !important;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            transition: background 0.2s;
            border: none;
        }

        .meet-btn:hover {
            background: #0f766e;
        }

        .meet-btn-disabled {
            background: #cbd5e1;
            color: #64748b !important;
            cursor: not-allowed;
        }

        /* Table Styles */
        .table-custom thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            border-bottom: 2px solid #f3f4f6;
            padding: 1rem 0.75rem;
        }

        .table-custom tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .status-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1em;
            padding-right: 2rem;
            padding-left: 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            width: auto;
            display: inline-block;
        }

        .btn-action-outline {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-action-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Override DataTables default sorting backgrounds */
        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1,
        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1,
        table.dataTable.display tbody tr:hover>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_1 {
            box-shadow: none !important;
            background-color: inherit !important;
        }

        table.dataTable.display tbody tr>td {
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')

    <!-- BOOKING PANEL -->
    <div class="card mb-4" id="classBookingSection">
        <div class="card-header">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Schedule a New Class Room
            </h4>
        </div>
        <form action="{{ route('admin.bookings.store') }}" method="POST" class="card-body p-4"
            onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Processing...';">
            @csrf
            <div class="booking-container">

                <!-- LEFT COLUMN: BOOKING FORM -->
                <div class="booking-form-section">
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="instrument" id="hiddenInstrument">
                    <input type="hidden" name="starts_at" id="hiddenStartsAt">
                    <input type="hidden" name="recurrence_mode" id="recurrenceMode" value="one-time">

                    <!-- Booking Mode Toggle -->
                    <div class="form-group mb-3">
                        <label class="form-label">Booking Mode</label>
                        <div class="segmented-control">
                            <button type="button" id="btnIndividualMode" class="segmented-control-btn active" onclick="setBookingMode('individual')">Individual</button>
                            <button type="button" id="btnGroupMode" class="segmented-control-btn" onclick="setBookingMode('group')">Group</button>
                        </div>
                    </div>
                    <input type="hidden" name="booking_mode" id="bookingMode" value="individual">

                    <div class="booking-grid">
                        <div class="form-group" id="studentSelectGroup">
                            <label class="form-label">Student Name</label>
                            <select name="student_id" id="bookStudent" class="form-control"
                                onchange="onStudentSelectChange()">
                                <option value="">-- Select Student --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" data-credits="{{ $student->credits }}"
                                        data-instrument="{{ $student->course->name ?? 'N/A' }}"
                                        data-teacher-id="{{ $student->teacher->id ?? '' }}"
                                        data-teacher="{{ $student->teacher->user->name ?? 'N/A' }}">
                                        {{ $student->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="groupSelectGroup" style="display: none;">
                            <label class="form-label">Select Group</label>
                            <select name="student_group_id" id="bookGroup" class="form-control"
                                onchange="onGroupSelectChange()">
                                <option value="">-- Select Group --</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" data-members="{{ count($group->members) }}"
                                        data-teacher-id="{{ $group->teacher_id ?? '' }}"
                                        data-teacher="{{ $group->teacher->user->name ?? 'N/A' }}">
                                        {{ $group->name }} ({{ count($group->members) }} students)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select Teacher</label>
                            <select name="teacher_id" id="bookTeacher" class="form-control" required
                                onchange="onTeacherSelectChange()">
                                <option value="">-- Select Teacher --</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Student Status Card (populated on select student) -->
                    <div id="studentStatusCard" class="student-info-card" style="display: none;">
                        <div class="student-info-item">
                            <span class="student-info-label">Assigned Teacher</span>
                            <span id="statusTeacher" class="student-info-value">-</span>
                        </div>
                        <div class="student-info-item">
                            <span class="student-info-label">Instrument / Subject</span>
                            <span id="statusInstrument" class="student-info-value">-</span>
                        </div>
                        <div class="student-info-item">
                            <span class="student-info-label">Allocated Credits</span>
                            <span id="statusCredits" class="student-info-value">-</span>
                        </div>
                    </div>

                    <div class="form-group mb-3" style="margin-top: 1rem;">
                        <label class="form-label">Booking Type</label>
                        <div class="segmented-control">
                            <button type="button" id="btnOneTime" class="segmented-control-btn active"
                                onclick="setRecurrenceMode('one-time')">One-time Class</button>
                            <button type="button" id="btnRecurring" class="segmented-control-btn"
                                onclick="setRecurrenceMode('recurring')">Recurring Weekly</button>
                        </div>
                    </div>

                    <!-- One Time Section -->
                    <div id="oneTimeSection" class="booking-grid">
                        <div class="form-group">
                            <label class="form-label">Select Date</label>
                            <input type="date" id="bookDate" class="form-control" required
                                onchange="onDateSelectChange()">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Start Time</label>
                            <select id="bookTime" class="form-control" required onchange="onTimeSelectChange()">
                                <!-- Populated via JS -->
                            </select>
                        </div>
                    </div>

                    <!-- Recurring Section -->
                    <div id="recurringSection" style="display: none; margin-top: 1rem;">
                        <div class="form-group mb-3">
                            <label class="form-label">Select Days of Week</label>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <label
                                        style="display: flex; align-items: center; gap: 4px; font-weight: normal; cursor: pointer;">
                                        <input type="checkbox" name="week_days[]" value="{{ strtoupper($day) }}"
                                            onchange="fetchRecurringAvailableSlots()"> {{ $day }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="bookTimeSlot">Preferred Time Slot</label>
                            <select id="bookTimeSlot" name="time_slot" class="form-control"
                                onchange="updateLivePreview()">
                                <!-- Populated via JS -->
                            </select>
                            <small class="text-muted" style="display:block; margin-top:4px;">Classes will be 40 minutes
                                long starting at this time.</small>
                        </div>
                        <div class="mb-2"
                            style="font-size: 13px; background: #fff3cd; padding: 10px; border-radius: 5px; color: #856404; border: 1px solid #ffeeba;">
                            ⚠️ <strong>Note:</strong> Recurring booking will automatically schedule classes and deduct
                            credits up to the student's current balance (<span id="recurrCreditsVal">0</span> credits).
                        </div>
                    </div>

                    <div class="form-group mb-3" style="margin-top: 1rem;">
                        <label class="form-label">Call Duration</label>
                        <input type="text" class="form-control" value="40 Minutes" readonly
                            style="background-color: var(--border-light)">
                        <input type="hidden" name="duration_minutes" value="40">
                    </div>

                    <!-- Warning Banner -->
                    <div id="bookingWarningText" class="text-danger font-semibold mb-2"
                        style="display:none; font-size: 0.8rem;"></div>

                    <button type="submit" id="btnSubmitBooking" class="btn btn-primary w-100"
                        style="margin-top: 1rem;">Create Scheduled Class Room</button>
                </div>

                <!-- RIGHT COLUMN: LIVE PREVIEW -->
                <div class="preview-section">
                    <div class="preview-card">
                        <h5 class="preview-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            Booking Live Preview
                        </h5>
                        <div class="preview-body">
                            <div class="preview-row">
                                <span class="preview-label">Student:</span>
                                <span id="previewStudent" class="preview-val">-</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Teacher:</span>
                                <span id="previewTeacher" class="preview-val">-</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Schedule Type:</span>
                                <span id="previewType" class="preview-val">-</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Start Time:</span>
                                <span id="previewStart" class="preview-val">-</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">End Time:</span>
                                <span id="previewEnd" class="preview-val">-</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Google Meet:</span>
                                <span class="preview-val meet-badge">Auto-Generated</span>
                            </div>

                            <!-- Recurring Dates list -->
                            <div id="previewOccurrencesGroup" style="display: none; margin-top: 0.5rem;">
                                <span class="preview-label" style="display: block; margin-bottom: 0.35rem;">Scheduled
                                    Session Dates:</span>
                                <div id="previewOccurrencesList" class="preview-occurrences-list">
                                    <!-- Populated dynamically -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- BOTTOM SECTION: ACTIVE BOOKINGS -->
    <div class="card mt-4">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem; margin: 0;">Active Scheduled Classes</h4>
            <form action="{{ route('admin.class-booking') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin: 0;">
                <input type="date" name="start_date" class="form-control" style="width: auto; padding: 0.25rem 0.5rem; font-size: 0.85rem;" value="{{ request('start_date', now()->format('Y-m-d')) }}">
                <span style="color: var(--text-muted); font-size: 0.85rem;">to</span>
                <input type="date" name="end_date" class="form-control" style="width: auto; padding: 0.25rem 0.5rem; font-size: 0.85rem;" value="{{ request('end_date') }}">
                <select name="status" class="form-control" style="width: auto; padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="reschedule_requested" {{ request('status') == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Req.</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;">Filter</button>
                @if(request()->anyFilled(['start_date', 'end_date', 'status']))
                    <a href="{{ route('admin.class-booking') }}" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.85rem; background: #e2e8f0; color: #475569; border: none; text-decoration: none;">Clear</a>
                @endif
            </form>
        </div>
        <div class="card-body p-0" style="overflow-x: auto;">
            <table class="table table-custom display responsive nowrap" id="bookingsTable"
                style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #ffffff;">
                        <th>Instrument</th>
                        <th>Date & Time</th>
                        <th>Student</th>
                        <th>Teacher</th>
                        <th>Google Meet</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr style="{{ $loop->iteration % 2 == 0 ? 'background: #ffffff;' : 'background: #f9fafb;' }}">
                            <td>{{ $booking->instrument }}</td>
                            <td>
                                <div class="font-semibold">{{ $booking->starts_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $booking->starts_at->format('h:i A') }} ({{ $booking->duration_minutes }} mins)
                                </div>
                            </td>
                            <td>
                                @if($booking->student_group_id)
                                    <span style="display:inline-block; padding: 2px 6px; background:#f0fdf4; color:#166534; border-radius:10px; font-size:0.75rem; margin-bottom:4px;">Group Class</span><br>
                                    {{ $booking->studentGroup->name ?? 'N/A' }}
                                @else
                                    {{ $booking->student->user->name ?? 'N/A' }}
                                @endif
                            </td>
                            <td>{{ $booking->teacher->user->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ $booking->google_meet_link ?? 'https://meet.google.com' }}" target="_blank"
                                    class="meet-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="23 7 16 12 23 17 23 7" />
                                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                                    </svg>
                                    Join Meet
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control status-select"
                                        onchange="updateClassStatus(this, '{{ route('admin.bookings.status', $booking) }}')"
                                        style="
                            @if ($booking->status == 'scheduled') background-color: #ffffff; color: #51040e; border: 1px solid rgba(81,4,14,0.3);
                            @elseif($booking->status == 'completed') background-color: var(--success-bg); color: var(--success); border: 1px solid var(--success);
                            @elseif($booking->status == 'reschedule_requested') background-color: rgba(81,4,14,0.07); color: #51040e; border: 1px solid rgba(81,4,14,0.35);
                            @elseif($booking->status == 'cancelled') background-color: #fff0f0; color: #dc2626; border: 1px solid #fca5a5; @endif
                        "
                                        {{ $booking->status === 'completed' || $booking->status === 'cancelled' ? 'disabled' : '' }}>
                                        <option value="scheduled" {{ $booking->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="reschedule_requested" {{ $booking->status == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Req.</option>
                                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled (+1 Refund)</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if ($booking->status === 'reschedule_requested')
                                    {{-- Header: who requested --}}
                                    <div style="font-size:0.72rem; margin-bottom:0.35rem; line-height:1.5;">
                                        <span style="font-weight:700; color: #51040e;">
                                            {{ $booking->rescheduled_by === 'Teacher' ? '👨‍🏫' : '🎓' }}
                                            Requested by {{ $booking->rescheduled_by ?? 'User' }}
                                        </span><br>
                                        @if($booking->reschedule_requested_starts_at)
                                            <span style="color:var(--text-muted);">📅 {{ $booking->reschedule_requested_starts_at->format('d M Y, h:i A') }}</span>
                                        @endif
                                    </div>

                                    {{-- Reason pill - clickable to show full reason --}}
                                    @if($booking->reschedule_reason)
                                        <div style="margin-bottom:0.4rem;">
                                            <span
                                                onclick="showRescheduleReason('{{ addslashes($booking->reschedule_reason) }}', '{{ $booking->rescheduled_by ?? 'User' }}', '{{ $booking->reschedule_requested_starts_at?->format('d M Y, h:i A') }}')"
                                                style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:600;color:#51040e;background:rgba(81,4,14,0.07);border:1px solid rgba(81,4,14,0.2);border-radius:20px;padding:0.15rem 0.55rem;cursor:pointer;"
                                                title="Click to view reason">
                                                💬 View Reason
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Approve / Reject buttons --}}
                                    <div style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                                        <form action="{{ route('admin.bookings.reschedule.approve', $booking) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="button"
                                                onclick="confirmRescheduleAction(this.closest('form'), 'approve', '{{ $booking->rescheduled_by ?? 'User' }}', '{{ $booking->reschedule_requested_starts_at?->format('d M Y, h:i A') }}')"
                                                style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.3rem 0.7rem;font-size:0.73rem;font-weight:700;color:#fff;background:#059669;border:none;border-radius:6px;cursor:pointer;font-family:var(--font-main);transition:all 0.2s;"
                                                onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">✓ Approve</button>
                                        </form>
                                        <form action="{{ route('admin.bookings.reschedule.reject', $booking) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="button"
                                                onclick="confirmRescheduleAction(this.closest('form'), 'reject', '{{ $booking->rescheduled_by ?? 'User' }}', '{{ $booking->reschedule_requested_starts_at?->format('d M Y, h:i A') }}')"
                                                style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.3rem 0.7rem;font-size:0.73rem;font-weight:700;color:#dc2626;background:#fff0f0;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;font-family:var(--font-main);transition:all 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff0f0'">✕ Reject</button>
                                        </form>
                                    </div>
                                @elseif ($booking->status === 'completed')
                                    <span style="color: var(--text-muted); font-size:0.78rem;">—</span>
                                @elseif ($booking->status === 'cancelled')
                                    <span style="color: var(--text-muted); font-size:0.78rem;">—</span>
                                @else
                                    <button type="button" class="btn-action-outline"
                                        onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->starts_at->format('Y-m-d\TH:i') }}', {{ $booking->teacher_id }})"
                                        style="padding:0.3rem 0.7rem; font-size:0.78rem;">
                                        ↺ Reschedule
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- RESCHEDULE MODAL -->
    <div id="rescheduleModal" class="modal-backdrop">
        <div class="modal" style="max-width: 550px; padding: 0;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-light); padding: 1.5rem;">
                <h3 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Request Class
                    Reschedule</h3>
                <button type="button" class="modal-close" onclick="closeRescheduleModal()"
                    style="font-size: 1.5rem; line-height: 1; color: #888;">&times;</button>
            </div>
            <form id="rescheduleForm" method="POST" style="margin: 0;" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 1.5rem; color: #4b5563;">
                    <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">Propose a new date and time for this session. The
                        other party will receive a notification to approve or decline this request.</p>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">Currently
                            Scheduled</label>
                        <input type="text" id="currentScheduledInput" class="form-control" readonly
                            style="background-color: #f9fafb; color: #6b7280;">
                    </div>

                    <div class="booking-grid" style="grid-template-columns: 1fr 1fr; display: grid; gap: 1rem;">
                        <div class="form-group mb-0" style="grid-column: span 2;">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">New Teacher</label>
                            <select id="reschTeacher" name="teacher_id" class="form-control" required onchange="fetchAdminRescheduleSlots()">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">New
                                Proposed Date</label>
                            <input type="date" id="reschDate" name="date" class="form-control" required
                                onchange="fetchAdminRescheduleSlots()">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">New
                                Proposed Time</label>
                            <select id="reschTime" name="time_slot" class="form-control" required disabled>
                                <option value="">Select date first</option>
                            </select>
                        </div>
                        <div class="form-group mb-0" style="grid-column: span 2;">
                            <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">Reason <span style="font-weight:400;color:var(--text-muted);">(optional)</span></label>
                            <textarea id="reschReason" name="reschedule_reason" class="form-control" rows="2" placeholder="Admin note for this reschedule…" style="resize:none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                    style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: center; gap: 1rem; background: #fff; border-bottom-left-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md);">
                    <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()"
                        style="background: #fff; color: #374151; border: 1px solid #d1d5db; padding: 0.5rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600;">Cancel
                        Request</button>
                    <button type="submit" class="btn btn-primary"
                        style="background: var(--primary); color: #fff; padding: 0.5rem 1.5rem; border: none; border-radius: var(--radius-sm); font-weight: 600;">Submit
                        Proposal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Reschedule Reason Modal ── --}}
    <div id="rescheduleReasonModal" class="modal-backdrop">
        <div class="modal" style="max-width: 460px; padding: 0;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-light); padding: 1.25rem 1.5rem;">
                <h3 class="font-semibold" style="font-size:1rem;">Reschedule Request Details</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('rescheduleReasonModal').classList.remove('show')" style="font-size:1.5rem;line-height:1;color:#888;">×</button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.3rem;">Requested By</div>
                    <div id="rrm-by" style="font-size:0.9rem;font-weight:700;color:#51040e;"></div>
                </div>
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.3rem;">Proposed Date & Time</div>
                    <div id="rrm-date" style="font-size:0.9rem;color:var(--text-main);"></div>
                </div>
                <div>
                    <div style="font-size:0.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.3rem;">Reason</div>
                    <div id="rrm-reason" style="font-size:0.9rem;color:var(--text-main);background:rgba(81,4,14,0.04);border:1px solid rgba(81,4,14,0.1);border-radius:8px;padding:0.75rem;line-height:1.55;"></div>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('rescheduleReasonModal').classList.remove('show')" style="padding:0.4rem 1.2rem;font-weight:600;">Close</button>
            </div>
        </div>
    </div>

    {{-- ── Confirm Action Modal ── --}}
    <div id="rescheduleConfirmModal" class="modal-backdrop">
        <div class="modal" style="max-width: 420px; padding: 0;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-light); padding: 1.25rem 1.5rem;">
                <h3 class="font-semibold" id="rcm-title" style="font-size:1rem;"></h3>
                <button type="button" class="modal-close" onclick="document.getElementById('rescheduleConfirmModal').classList.remove('show')" style="font-size:1.5rem;line-height:1;color:#888;">×</button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <p id="rcm-message" style="font-size:0.9rem;color:var(--text-main);line-height:1.55;"></p>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" onclick="document.getElementById('rescheduleConfirmModal').classList.remove('show')" style="padding:0.4rem 1.2rem;font-weight:600;border:1px solid var(--border-color);background:#fff;border-radius:var(--radius-sm);cursor:pointer;font-family:var(--font-main);">Cancel</button>
                <button type="button" id="rcm-confirm-btn" style="padding:0.4rem 1.4rem;font-weight:700;border:none;border-radius:var(--radius-sm);cursor:pointer;font-family:var(--font-main);" onclick="rcmConfirm()">Confirm</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        var DAYS_OF_WEEK = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                "order": [],
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search bookings..."
                }
            });

            populateReschTimeDropdown();

            // Initialize bookTime as empty since date/teacher aren't selected
            document.getElementById('bookTime').innerHTML = '<option value="">-- Select teacher and date first --</option>';
            document.getElementById('bookTimeSlot').innerHTML = '<option value="">-- Select teacher and days first --</option>';
        });

        function setBookingMode(mode) {
            document.getElementById('bookingMode').value = mode;
            if (mode === 'group') {
                document.getElementById('btnGroupMode').classList.add('active');
                document.getElementById('btnIndividualMode').classList.remove('active');
                document.getElementById('studentSelectGroup').style.display = 'none';
                document.getElementById('groupSelectGroup').style.display = 'block';
                document.getElementById('bookStudent').removeAttribute('required');
                document.getElementById('bookGroup').setAttribute('required', 'required');
                onGroupSelectChange();
            } else {
                document.getElementById('btnIndividualMode').classList.add('active');
                document.getElementById('btnGroupMode').classList.remove('active');
                document.getElementById('groupSelectGroup').style.display = 'none';
                document.getElementById('studentSelectGroup').style.display = 'block';
                document.getElementById('bookGroup').removeAttribute('required');
                document.getElementById('bookStudent').setAttribute('required', 'required');
                onStudentSelectChange();
            }
        }

        function onGroupSelectChange() {
            const select = document.getElementById('bookGroup');
            const studentStatusCard = document.getElementById('studentStatusCard');
            const warning = document.getElementById('bookingWarningText');
            const btnSubmit = document.getElementById('btnSubmitBooking');

            if (!select || !select.value) {
                studentStatusCard.style.display = 'none';
                warning.style.display = 'none';
                btnSubmit.disabled = false;
                updateLivePreview();
                return;
            }

            const selectedOpt = select.options[select.selectedIndex];
            const membersCount = parseInt(selectedOpt.getAttribute('data-members') || '0', 10);
            const teacher = selectedOpt.getAttribute('data-teacher');
            const teacherId = selectedOpt.getAttribute('data-teacher-id');

            const teacherSelect = document.getElementById('bookTeacher');
            if (teacherSelect && teacherId) {
                teacherSelect.value = teacherId;
            }

            document.getElementById('statusTeacher').textContent = teacher;
            document.getElementById('statusInstrument').textContent = 'Group Class';
            document.getElementById('statusCredits').textContent = `${membersCount} Members`;
            studentStatusCard.style.display = 'flex';

            document.getElementById('hiddenInstrument').value = 'Group Music Class';
            
            warning.style.display = 'none';
            btnSubmit.disabled = false;
            document.getElementById('recurrCreditsVal').textContent = membersCount + " members";

            updateLivePreview();
        }

        function onStudentSelectChange() {
            const select = document.getElementById('bookStudent');
            const studentStatusCard = document.getElementById('studentStatusCard');
            const warning = document.getElementById('bookingWarningText');
            const btnSubmit = document.getElementById('btnSubmitBooking');
            const recurrenceSection = document.getElementById('recurrenceSection');

            if (!select || !select.value) {
                studentStatusCard.style.display = 'none';
                warning.style.display = 'none';
                btnSubmit.disabled = false;
                updateLivePreview();
                return;
            }

            const selectedOpt = select.options[select.selectedIndex];
            const credits = parseInt(selectedOpt.getAttribute('data-credits') || '0', 10);
            const instrument = selectedOpt.getAttribute('data-instrument');
            const teacher = selectedOpt.getAttribute('data-teacher');
            const teacherId = selectedOpt.getAttribute('data-teacher-id');

            const teacherSelect = document.getElementById('bookTeacher');
            if (teacherSelect && teacherId) {
                teacherSelect.value = teacherId;
            }

            document.getElementById('statusTeacher').textContent = teacher;
            document.getElementById('statusInstrument').textContent = instrument;
            document.getElementById('statusCredits').textContent = `${credits} Class${credits === 1 ? '' : 'es'}`;
            studentStatusCard.style.display = 'flex';

            document.getElementById('hiddenInstrument').value = instrument;

            if (credits <= 0) {
                warning.textContent =
                    `⚠️ Cannot book: student has 0 remaining class credits! Please purchase packages first.`;
                warning.style.display = 'block';
                btnSubmit.disabled = true;
                document.getElementById('recurrCreditsVal').textContent = '0';
            } else {
                warning.style.display = 'none';
                btnSubmit.disabled = false;
                document.getElementById('recurrCreditsVal').textContent = credits;
            }

            updateLivePreview();
        }

        function onDateSelectChange() {
            fetchAvailableSlots();
            updateLivePreview();
        }

        function setRecurrenceMode(mode) {
            const btnOneTime = document.getElementById("btnOneTime");
            const btnRecurring = document.getElementById("btnRecurring");
            const recurrenceMode = document.getElementById("recurrenceMode");

            recurrenceMode.value = mode;

            if (mode === "one-time") {
                btnOneTime.classList.add("active");
                btnRecurring.classList.remove("active");
                document.getElementById('oneTimeSection').style.display = 'grid';
                document.getElementById('recurringSection').style.display = 'none';
                document.getElementById('bookDate').setAttribute('required', 'required');
                document.getElementById('bookTime').setAttribute('required', 'required');
                document.getElementById('bookTimeSlot').removeAttribute('required');
            } else {
                btnOneTime.classList.remove("active");
                btnRecurring.classList.add("active");
                document.getElementById('oneTimeSection').style.display = 'none';
                document.getElementById('recurringSection').style.display = 'block';
                document.getElementById('bookDate').removeAttribute('required');
                document.getElementById('bookTime').removeAttribute('required');
                document.getElementById('bookTimeSlot').setAttribute('required', 'required');
            }

            updateLivePreview();
        }

        function onTimeSelectChange() {
            updateLivePreview();
        }

        function onTeacherSelectChange() {
            fetchAvailableSlots();
            fetchRecurringAvailableSlots();
            updateLivePreview();
        }

        function fetchAvailableSlots() {
            const teacherSelect = document.getElementById('bookTeacher');
            const dateInput = document.getElementById('bookDate');
            const select = document.getElementById('bookTime');
            const btnSubmit = document.getElementById('btnSubmitBooking');
            const mode = document.getElementById('recurrenceMode').value;

            if (mode === 'recurring') {
                updateLivePreview();
                btnSubmit.disabled = false;
                return;
            }

            if (!teacherSelect || !teacherSelect.value || !dateInput || !dateInput.value) {
                select.innerHTML = '<option value="">-- Select teacher and date first --</option>';
                updateLivePreview();
                return;
            }

            select.innerHTML = '<option value="">Loading slots...</option>';
            btnSubmit.disabled = true;

            fetch(`/admin/teachers/${teacherSelect.value}/slots?date=${dateInput.value}`)
                .then(res => res.json())
                .then(data => {
                    select.innerHTML = '';
                    if (data.slots && data.slots.length > 0) {
                        data.slots.forEach(slot => {
                            // Extract only "HH:mm" from "Y-m-d H:i:s" so combining with date gives valid ISO datetime
                            const timePart = slot.start_time.includes('T')
                                ? slot.start_time.split('T')[1].substring(0, 5)
                                : slot.start_time.split(' ')[1].substring(0, 5);
                            select.innerHTML +=
                                `<option value="${timePart}">${slot.display_time}</option>`;
                        });
                        btnSubmit.disabled = false;
                    } else {
                        select.innerHTML = '<option value="">-- No slots available --</option>';
                    }
                    updateLivePreview();
                })
                .catch(err => {
                    select.innerHTML = '<option value="">-- Error loading slots --</option>';
                    updateLivePreview();
                });
        }

        function getNextDateForDay(dayStr) {
            const dayMap = {'SUN':0, 'MON':1, 'TUE':2, 'WED':3, 'THU':4, 'FRI':5, 'SAT':6};
            const target = dayMap[dayStr];
            const d = new Date();
            d.setDate(d.getDate() + 1);
            for (let i=0; i<7; i++) {
                if (d.getDay() === target) {
                    return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
                }
                d.setDate(d.getDate() + 1);
            }
            return null;
        }

        function fetchRecurringAvailableSlots() {
            const teacherSelect = document.getElementById('bookTeacher');
            const selectRecurring = document.getElementById('bookTimeSlot');
            const checkedDays = Array.from(document.querySelectorAll('input[name="week_days[]"]:checked')).map(el => el.value);
            
            if (!teacherSelect || !teacherSelect.value) {
                selectRecurring.innerHTML = '<option value="">-- Select teacher first --</option>';
                updateLivePreview();
                return;
            }
            
            if (checkedDays.length === 0) {
                selectRecurring.innerHTML = '<option value="">-- Select days of week first --</option>';
                updateLivePreview();
                return;
            }

            const nextDate = getNextDateForDay(checkedDays[0]);
            if (!nextDate) return;

            selectRecurring.innerHTML = '<option value="">Loading slots...</option>';

            fetch(`/admin/teachers/${teacherSelect.value}/slots?date=${nextDate}`)
                .then(res => res.json())
                .then(data => {
                    selectRecurring.innerHTML = '';
                    if (data.slots && data.slots.length > 0) {
                        data.slots.forEach(slot => {
                            const timePart = slot.start_time.split(' ')[1]; // "H:i:s"
                            const timePartShort = timePart.substring(0, 5); // "H:i"
                            selectRecurring.innerHTML += `<option value="${timePartShort}">${slot.display_time}</option>`;
                        });
                    } else {
                        selectRecurring.innerHTML = '<option value="">-- No slots available --</option>';
                    }
                    updateLivePreview();
                })
                .catch(err => {
                    selectRecurring.innerHTML = '<option value="">-- Error loading slots --</option>';
                    updateLivePreview();
                });
        }

        function getEndTime(startTimeVal) {
            if (!startTimeVal) return "-";
            const [hStr, mStr] = startTimeVal.split(':');
            let h = parseInt(hStr, 10);
            let m = parseInt(mStr, 10);

            m += 40;
            if (m >= 60) {
                h = (h + 1) % 24;
                m -= 60;
            }

            const period = h >= 12 ? "PM" : "AM";
            let displayHour = h % 12;
            if (displayHour === 0) displayHour = 12;
            const displayMin = String(m).padStart(2, '0');

            return `${String(displayHour).padStart(2, '0')}:${displayMin} ${period}`;
        }

        function updateLivePreview() {
            const bookStudent = document.getElementById("bookStudent");
            const bookDate = document.getElementById("bookDate");
            const bookTime = document.getElementById("bookTime");
            const recurrenceMode = document.getElementById("recurrenceMode");
            const bookWeeks = document.getElementById("bookWeeks");

            const previewStudent = document.getElementById("previewStudent");
            const previewTeacher = document.getElementById("previewTeacher");
            const previewType = document.getElementById("previewType");
            const previewStart = document.getElementById("previewStart");
            const previewEnd = document.getElementById("previewEnd");

            const previewOccurrencesGroup = document.getElementById("previewOccurrencesGroup");
            const previewOccurrencesList = document.getElementById("previewOccurrencesList");

            previewStudent.textContent = "-";
            previewTeacher.textContent = "-";
            previewType.textContent = "-";
            previewStart.textContent = "-";
            previewEnd.textContent = "-";
            previewOccurrencesGroup.style.display = "none";
            previewOccurrencesList.innerHTML = "";

            const mode = document.getElementById("bookingMode").value;
            let selectedOpt = null;

            if (mode === "individual") {
                if (!bookStudent || !bookStudent.value) return;
                selectedOpt = bookStudent.options[bookStudent.selectedIndex];
                previewStudent.textContent = selectedOpt.text.split(' (')[0];
            } else {
                const bookGroup = document.getElementById("bookGroup");
                if (!bookGroup || !bookGroup.value) return;
                selectedOpt = bookGroup.options[bookGroup.selectedIndex];
                previewStudent.textContent = selectedOpt.text.split(' (')[0] + " (Group)";
            }

            const bookTeacher = document.getElementById("bookTeacher");
            if (bookTeacher && bookTeacher.value) {
                previewTeacher.textContent = bookTeacher.options[bookTeacher.selectedIndex].text;
            } else if (selectedOpt) {
                previewTeacher.textContent = selectedOpt.getAttribute('data-teacher');
            }

            let timeStr = "-";
            let endTimeStr = "-";
            if (bookTime && bookTime.value) {
                const timeOpt = bookTime.options[bookTime.selectedIndex];
                timeStr = timeOpt ? timeOpt.text.split(' - ')[0] : "-";
                endTimeStr = getEndTime(bookTime.value);

                if (bookDate && bookDate.value) {
                    const combined = bookDate.value + 'T' + bookTime.value;
                    document.getElementById('hiddenStartsAt').value = combined;
                }
            }

            previewStart.textContent = timeStr;
            previewEnd.textContent = endTimeStr;

            const isRecurring = recurrenceMode.value === "recurring";

            if (isRecurring) {
                const timeSlotInput = document.getElementById("bookTimeSlot");
                const checkedDays = Array.from(document.querySelectorAll('input[name="week_days[]"]:checked')).map(el => el
                    .nextSibling.textContent.trim());
                const credits = parseInt(document.getElementById('recurrCreditsVal').textContent || '0', 10);

                previewType.textContent = `Recurring Weekly (Uses ${credits} Credits)`;

                let timeSlotStr = "-";
                let endSlotStr = "-";
                if (timeSlotInput && timeSlotInput.value) {
                    const timeOpt = timeSlotInput.options ? timeSlotInput.options[timeSlotInput.selectedIndex] : null;
                    timeSlotStr = timeOpt ? timeOpt.text.split(' - ')[0] : "-";
                    endSlotStr = getEndTime(timeSlotInput.value);
                }

                previewStart.textContent = timeSlotStr;
                previewEnd.textContent = endSlotStr;

                if (checkedDays.length > 0) {
                    previewOccurrencesGroup.style.display = "block";
                    previewOccurrencesList.innerHTML =
                        `<div class="preview-occurrence-item"><span class="preview-occurrence-date" style="width: 100%;">Every ${checkedDays.join(', ')} (up to ${credits} classes)</span></div>`;
                } else {
                    previewOccurrencesGroup.style.display = "none";
                    previewOccurrencesList.innerHTML = "";
                }
            } else {
                previewType.textContent = "One-time Class";
                if (bookDate && bookDate.value) {
                    previewOccurrencesGroup.style.display = "block";
                    const [year, month, day] = bookDate.value.split('-').map(num => parseInt(num, 10));
                    const startDate = new Date(year, month - 1, day);
                    const dateStr = startDate.toLocaleDateString('en-US', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    previewOccurrencesList.innerHTML = `
                    <div class="preview-occurrence-item" style="border-left-color: var(--secondary);">
                    <span class="preview-occurrence-date">${dateStr}</span>
                    <span class="preview-occurrence-index">Single Session</span>
                    </div>
                `;
                }
            }
        }

        function openRescheduleModal(id, currentDateTime, teacherId) {
            // Set form action
            document.getElementById('rescheduleForm').action = `/admin/bookings/${id}/reschedule`;

            // Show current schedule in read-only field
            const dateObj = new Date(currentDateTime);
            const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
            document.getElementById('currentScheduledInput').value = dateObj.toLocaleString('en-US', options);

            // Pre-select teacher, reset date/time/reason
            const teacherSelect = document.getElementById('reschTeacher');
            teacherSelect.value = teacherId || '';
            document.getElementById('reschDate').value = '';
            document.getElementById('reschTime').innerHTML = '<option value="">Select a date first</option>';
            document.getElementById('reschTime').disabled = true;
            const reasonField = document.getElementById('reschReason');
            if (reasonField) reasonField.value = '';

            document.getElementById('rescheduleModal').classList.add('show');
        }

        function fetchAdminRescheduleSlots() {
            const teacherId = document.getElementById('reschTeacher').value;
            const date      = document.getElementById('reschDate').value;
            const timeSelect = document.getElementById('reschTime');

            if (!teacherId || !date) {
                timeSelect.innerHTML = '<option value="">Select teacher & date first</option>';
                timeSelect.disabled = true;
                return;
            }

            timeSelect.innerHTML = '<option value="">Loading available slots…</option>';
            timeSelect.disabled = true;

            fetch(`/admin/reschedule/slots?teacher_id=${teacherId}&date=${date}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(slots => {
                if (!Array.isArray(slots) || slots.length === 0) {
                    timeSelect.innerHTML = '<option value="">No available slots on this date</option>';
                    return;
                }
                timeSelect.innerHTML = '<option value="">— Select a time slot —</option>';
                slots.forEach(slot => {
                    // Use display_time from API (already formatted as "08:00 AM - 08:40 AM")
                    const label = slot.display_time;
                    const opt   = document.createElement('option');
                    opt.value   = label;
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

        function disableAdminSubmit(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="margin-right: 5px;"></span> Processing...';
            }
        }

        function updateClassStatus(selectElement, url) {
            const status = selectElement.value;
            const form = selectElement.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');

                        // If credit was refunded, update the student dropdown
                        if (data.refunded && data.student_id) {
                            const studentSelect = document.getElementById('bookStudent');
                            if (studentSelect) {
                                const opt = studentSelect.querySelector(`option[value="${data.student_id}"]`);
                                if (opt) {
                                    const currentCredits = parseInt(opt.getAttribute('data-credits') || '0', 10);
                                    opt.setAttribute('data-credits', currentCredits + 1);

                                    // If this student is currently selected, trigger the change event to update the UI
                                    if (studentSelect.value == data.student_id) {
                                        onStudentSelectChange();
                                    }
                                }
                            }
                        }

                        // Update styling based on new status
                        if (status === 'completed') {
                            selectElement.style.backgroundColor = 'var(--success-bg)';
                            selectElement.style.color = 'var(--success)';
                            selectElement.style.borderColor = 'var(--success)';
                            selectElement.disabled = true;
                        } else if (status === 'cancelled') {
                            selectElement.style.backgroundColor = '#ffffff';
                            selectElement.style.color = '#374151';
                            selectElement.style.borderColor = '#9ca3af';
                            selectElement.disabled = true;
                        } else if (status === 'scheduled') {
                            selectElement.style.backgroundColor = '#ffffff';
                            selectElement.style.color = '#2563eb';
                            selectElement.style.borderColor = '#3b82f6';
                        }
                    } else {
                        showToast(data.error || 'Failed to update status', 'error');
                        // Revert selection
                        selectElement.value = selectElement.getAttribute('data-original');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while updating the status.', 'error');
                });
        }

        // showToast is now handled globally in app.js

        // Store original value to revert if ajax fails
        document.querySelectorAll('.status-select').forEach(select => {
            select.setAttribute('data-original', select.value);
            select.addEventListener('focus', function() {
                this.setAttribute('data-original', this.value);
            });
        });

        // ── Reschedule Reason Modal ──────────────────────────────
        function showRescheduleReason(reason, requestedBy, proposedDate) {
            document.getElementById('rrm-by').textContent = requestedBy;
            document.getElementById('rrm-date').textContent = proposedDate || '—';
            document.getElementById('rrm-reason').textContent = reason || 'No reason provided.';
            document.getElementById('rescheduleReasonModal').classList.add('show');
        }

        // ── Confirm Approve / Reject ─────────────────────────────
        let _rcmForm = null;

        function confirmRescheduleAction(form, action, requestedBy, proposedDate) {
            _rcmForm = form;
            const isApprove = action === 'approve';

            document.getElementById('rcm-title').textContent = isApprove ? '✓ Approve Reschedule?' : '✕ Reject Reschedule?';
            document.getElementById('rcm-message').innerHTML = isApprove
                ? `You are about to <strong>approve</strong> the reschedule request from <strong>${requestedBy}</strong>.<br><br>
                   The class will be rescheduled to <strong>${proposedDate || 'the proposed time'}</strong> and notifications will be sent automatically.`
                : `You are about to <strong>reject</strong> the reschedule request from <strong>${requestedBy}</strong>.<br><br>
                   The class will remain on its <strong>original schedule</strong> and the requester will be notified.`;

            const btn = document.getElementById('rcm-confirm-btn');
            btn.textContent = isApprove ? '✓ Yes, Approve' : '✕ Yes, Reject';
            btn.style.background = isApprove ? '#059669' : '#dc2626';
            btn.style.color = '#fff';

            document.getElementById('rescheduleConfirmModal').classList.add('show');
        }

        function rcmConfirm() {
            if (_rcmForm) {
                document.getElementById('rescheduleConfirmModal').classList.remove('show');
                _rcmForm.submit();
                _rcmForm = null;
            }
        }
    </script>
@endpush
