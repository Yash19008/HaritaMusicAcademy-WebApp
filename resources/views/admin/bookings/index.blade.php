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
    table.dataTable.display tbody tr.odd > .sorting_1, table.dataTable.order-column.stripe tbody tr.odd > .sorting_1,
    table.dataTable.display tbody tr.even > .sorting_1, table.dataTable.order-column.stripe tbody tr.even > .sorting_1,
    table.dataTable.display tbody tr:hover > .sorting_1, table.dataTable.order-column.hover tbody tr:hover > .sorting_1 {
        box-shadow: none !important;
        background-color: inherit !important;
    }
    table.dataTable.display tbody tr > td {
        box-shadow: none !important;
    }
</style>
@endpush

@section('content')

    <!-- BOOKING PANEL -->
    <div class="card mb-4" id="classBookingSection">
      <div class="card-header">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Schedule a New Class Room</h4>
      </div>
      <form action="{{ route('admin.bookings.store') }}" method="POST" class="card-body p-4">
        @csrf
        <div class="booking-container">
          
          <!-- LEFT COLUMN: BOOKING FORM -->
          <div class="booking-form-section">
            <!-- Hidden Inputs -->
            <input type="hidden" name="instrument" id="hiddenInstrument">
            <input type="hidden" name="starts_at" id="hiddenStartsAt">
            <input type="hidden" name="recurrence_mode" id="recurrenceMode" value="one-time">
            <input type="hidden" name="weeks_count" id="hiddenWeeksCount" value="1">

            <div class="booking-grid">
              <div class="form-group">
                <label class="form-label">Student Name</label>
                <select name="student_id" id="bookStudent" class="form-control" required onchange="onStudentSelectChange()">
                  <option value="">-- Select Student --</option>
                  @foreach($students as $student)
                    <option value="{{ $student->id }}" data-credits="{{ $student->credits }}" data-instrument="{{ $student->course->name ?? 'N/A' }}" data-teacher-id="{{ $student->teacher->id ?? '' }}" data-teacher="{{ $student->teacher->user->name ?? 'N/A' }}">{{ $student->user->name ?? 'N/A' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Select Date</label>
                <input type="date" id="bookDate" class="form-control" required onchange="onDateSelectChange()">
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

            <div class="booking-grid">
              <div class="form-group">
                <label class="form-label">Start Time</label>
                <select id="bookTime" class="form-control" required onchange="onTimeSelectChange()">
                  <!-- Populated via JS -->
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Select Teacher</label>
                <select name="teacher_id" id="bookTeacher" class="form-control" required onchange="onTeacherSelectChange()">
                  <option value="">-- Select Teacher --</option>
                  @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? 'N/A' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Call Duration</label>
                <input type="text" class="form-control" value="40 Minutes" readonly style="background-color: var(--border-light)">
                <input type="hidden" name="duration_minutes" value="40">
              </div>
            </div>

            <!-- Recurrence Settings (Initially hidden until date selected) -->
            <div id="recurrenceSection" style="display: none; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label class="form-label">Booking Type</label>
                    <div class="segmented-control">
                    <button type="button" id="btnOneTime" class="segmented-control-btn active" onclick="setRecurrenceMode('one-time')">One-time Class</button>
                    <button type="button" id="btnRecurring" class="segmented-control-btn" onclick="setRecurrenceMode('recurring')">Recurring Weekly</button>
                    </div>
                </div>

                <div id="recurringWeeksGroup" class="form-group" style="display: none;">
                    <label class="form-label">Number of Weekly Occurrences</label>
                    <select id="bookWeeks" class="form-control" onchange="onWeeksSelectChange()">
                    <!-- Dynamic based on credits -->
                    </select>
                    <span class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                    The class will repeat weekly on <span id="detectedDayLabel" class="font-semibold">-</span>s. Capped by student's available credits.
                    </span>
                </div>
            </div>

            <!-- Warning Banner -->
            <div id="bookingWarningText" class="text-danger font-semibold mb-2" style="display:none; font-size: 0.8rem;"></div>

            <button type="submit" id="btnSubmitBooking" class="btn btn-primary w-100" style="margin-top: 1rem;">Create Scheduled Class Room</button>
          </div>

          <!-- RIGHT COLUMN: LIVE PREVIEW -->
          <div class="preview-section">
            <div class="preview-card">
              <h5 class="preview-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
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
                  <span class="preview-label" style="display: block; margin-bottom: 0.35rem;">Scheduled Session Dates:</span>
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
    <div class="card-header">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Active Scheduled Classes</h4>
    </div>
    <div class="card-body p-0" style="overflow-x: auto;">
        <table class="table table-custom display responsive nowrap" id="bookingsTable" style="width:100%; border-collapse: collapse;">
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
              @foreach($bookings as $booking)
              <tr style="{{ $loop->iteration % 2 == 0 ? 'background: #ffffff;' : 'background: #f9fafb;' }}">
                  <td>{{ $booking->instrument }}</td>
                  <td>
                    <div class="font-semibold">{{ $booking->starts_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $booking->starts_at->format('h:i A') }} ({{ $booking->duration_minutes }} mins)</div>
                  </td>
                  <td>{{ $booking->student->user->name ?? 'N/A' }}</td>
                  <td>{{ $booking->teacher->user->name ?? 'N/A' }}</td>
                  <td>
                      <a href="{{ $booking->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="meet-btn">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                          Join Meet
                      </a>
                  </td>
                  <td>
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control status-select" onchange="this.form.submit()" style="
                            @if($booking->status == 'scheduled') background-color: #ffffff; color: #2563eb; border: 1px solid #3b82f6;
                            @elseif($booking->status == 'completed') background-color: var(--success-bg); color: var(--success); border: 1px solid var(--success);
                            @elseif($booking->status == 'reschedule_requested') background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;
                            @elseif($booking->status == 'cancelled') background-color: #ffffff; color: #374151; border: 1px solid #9ca3af;
                            @endif
                        " {{ $booking->status === 'completed' ? 'disabled' : '' }}>
                            <option value="scheduled" {{ $booking->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="reschedule_requested" {{ $booking->status == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Req.</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed (-1 Credit)</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                  </td>
                  <td>
                      @if($booking->status !== 'completed')
                          <button type="button" class="btn-action-outline" onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->starts_at->format('Y-m-d\TH:i') }}')">Reschedule</button>
                      @else
                          <span class="text-muted" style="font-size:0.8rem;">Locked</span>
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
        <h3 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Request Class Reschedule</h3>
        <button type="button" class="modal-close" onclick="closeRescheduleModal()" style="font-size: 1.5rem; line-height: 1; color: #888;">&times;</button>
    </div>
    <form id="rescheduleForm" method="POST" style="margin: 0;">
    @csrf
    @method('PUT')
    <div class="modal-body" style="padding: 1.5rem; color: #4b5563;">
        <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">Propose a new date and time for this session. The other party will receive a notification to approve or decline this request.</p>

        <div class="form-group mb-3">
            <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">Currently Scheduled</label>
            <input type="text" id="currentScheduledInput" class="form-control" readonly style="background-color: #f9fafb; color: #6b7280;">
        </div>

        <div class="booking-grid" style="grid-template-columns: 1fr 1fr; display: grid; gap: 1rem;">
            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">New Proposed Date</label>
                <input type="date" id="reschDate" class="form-control" required onchange="updateReschStartsAt()">
            </div>
            <div class="form-group mb-0">
                <label class="form-label" style="font-size: 0.8rem; font-weight: 600; color: #374151;">New Proposed Time</label>
                <select id="reschTime" class="form-control" required onchange="updateReschStartsAt()">
                    <!-- Populated via JS -->
                </select>
            </div>
        </div>
        <input type="hidden" name="starts_at" id="reschStartsAt">
    </div>
    <div class="modal-footer" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); display: flex; justify-content: center; gap: 1rem; background: #fff; border-bottom-left-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md);">
        <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()" style="background: #fff; color: #374151; border: 1px solid #d1d5db; padding: 0.5rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600;">Cancel Request</button>
        <button type="submit" class="btn btn-primary" style="background: var(--primary); color: #fff; padding: 0.5rem 1.5rem; border: none; border-radius: var(--radius-sm); font-weight: 600;">Submit Proposal</button>
    </div>
    </form>
</div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#bookingsTable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 10,
        "language": {
            "search": "",
            "searchPlaceholder": "Search bookings..."
        }
    });

    populateTimeDropdown();
    populateReschTimeDropdown();
});

    function onStudentSelectChange() {
        const select = document.getElementById('bookStudent');
        const studentStatusCard = document.getElementById('studentStatusCard');
        const warning = document.getElementById('bookingWarningText');
        const btnSubmit = document.getElementById('btnSubmitBooking');
        const recurrenceSection = document.getElementById('recurrenceSection');
        const bookWeeks = document.getElementById('bookWeeks');

        if (!select || !select.value) {
            studentStatusCard.style.display = 'none';
            recurrenceSection.style.display = 'none';
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
            warning.textContent = `⚠️ Cannot book: student has 0 remaining class credits! Please purchase packages first.`;
            warning.style.display = 'block';
            btnSubmit.disabled = true;
            recurrenceSection.style.display = 'none';
            bookWeeks.innerHTML = '';
        } else {
            warning.style.display = 'none';
            btnSubmit.disabled = false;
            
            let weeksOptions = [];
            const maxWeeks = credits;
            for (let i = 1; i <= maxWeeks; i++) {
                weeksOptions.push(`<option value="${i}">${i} Week${i === 1 ? '' : 's'} (${i} Credit${i === 1 ? '' : 's'})</option>`);
            }
            bookWeeks.innerHTML = weeksOptions.join('');
            bookWeeks.value = Math.min(maxWeeks, 4);
            
            // Trigger date check to show recurrence section if date is already picked
            onDateSelectChange();
        }

        updateLivePreview();
    }

    function onDateSelectChange() {
        const dateInput = document.getElementById("bookDate");
        const recurrenceSection = document.getElementById("recurrenceSection");
        const detectedDayLabel = document.getElementById("detectedDayLabel");
        
        if (!dateInput || !dateInput.value) {
            recurrenceSection.style.display = "none";
            return;
        }
        
        const select = document.getElementById('bookStudent');
        const credits = select && select.value ? parseInt(select.options[select.selectedIndex].getAttribute('data-credits') || '0', 10) : 0;
        
        if (credits > 0) {
            const [year, month, day] = dateInput.value.split('-').map(num => parseInt(num, 10));
            const dateObj = new Date(year, month - 1, day);
            const dayName = DAYS_OF_WEEK[dateObj.getDay()];

            detectedDayLabel.textContent = dayName;
            recurrenceSection.style.display = "flex";
        }
        
        updateLivePreview();
    }

    function setRecurrenceMode(mode) {
        const btnOneTime = document.getElementById("btnOneTime");
        const btnRecurring = document.getElementById("btnRecurring");
        const recurringWeeksGroup = document.getElementById("recurringWeeksGroup");
        const recurrenceMode = document.getElementById("recurrenceMode");
        const hiddenWeeksCount = document.getElementById("hiddenWeeksCount");

        recurrenceMode.value = mode;

        if (mode === "one-time") {
            btnOneTime.classList.add("active");
            btnRecurring.classList.remove("active");
            recurringWeeksGroup.style.display = "none";
            hiddenWeeksCount.value = "1";
        } else {
            btnOneTime.classList.remove("active");
            btnRecurring.classList.add("active");
            recurringWeeksGroup.style.display = "block";
            hiddenWeeksCount.value = document.getElementById("bookWeeks").value;
        }

        updateLivePreview();
    }

    function onWeeksSelectChange() {
        document.getElementById("hiddenWeeksCount").value = document.getElementById("bookWeeks").value;
        updateLivePreview();
    }

    function onTimeSelectChange() {
        updateLivePreview();
    }

    function onTeacherSelectChange() {
        updateLivePreview();
    }

    function populateTimeDropdown() {
      const select = document.getElementById('bookTime');
      const options = [];
      for (let hour = 8; hour <= 26; hour++) {
        for (let min = 0; min < 60; min += 30) {
          if (hour === 26 && min > 0) break;
          const adjustedHour = hour % 24;
          const period = hour >= 24 || adjustedHour < 12 ? "AM" : "PM";
          let displayHour = adjustedHour % 12;
          if (displayHour === 0) displayHour = 12;
          const displayMin = String(min).padStart(2, '0');
          const timeStr = `${String(displayHour).padStart(2, '0')}:${displayMin} ${period}`;
          const valStr = `${String(adjustedHour).padStart(2, '0')}:${displayMin}`;
          options.push(`<option value="${valStr}">${timeStr}</option>`);
        }
      }
      select.innerHTML = options.join('');
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

        if (!bookStudent || !bookStudent.value) return;

        const selectedOpt = bookStudent.options[bookStudent.selectedIndex];
        previewStudent.textContent = selectedOpt.text.split(' (')[0];
        
        const bookTeacher = document.getElementById("bookTeacher");
        if (bookTeacher && bookTeacher.value) {
            previewTeacher.textContent = bookTeacher.options[bookTeacher.selectedIndex].text;
        } else {
            previewTeacher.textContent = selectedOpt.getAttribute('data-teacher');
        }

        let timeStr = "-";
        let endTimeStr = "-";
        if (bookTime && bookTime.value) {
            const timeOpt = bookTime.options[bookTime.selectedIndex];
            timeStr = timeOpt ? timeOpt.text : "-";
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
            const weeksCount = parseInt(bookWeeks.value, 10) || 1;
            previewType.textContent = `Recurring Weekly (${weeksCount} Classes)`;

            if (bookDate && bookDate.value) {
                previewOccurrencesGroup.style.display = "block";
                const [year, month, day] = bookDate.value.split('-').map(num => parseInt(num, 10));
                const startDate = new Date(year, month - 1, day);

                let listItemsHtml = [];
                for (let i = 0; i < weeksCount; i++) {
                    const occurrenceDate = new Date(startDate.getTime());
                    occurrenceDate.setDate(startDate.getDate() + (i * 7));

                    const dateStr = occurrenceDate.toLocaleDateString('en-US', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });

                    listItemsHtml.push(`
                    <div class="preview-occurrence-item">
                        <span class="preview-occurrence-date">${dateStr}</span>
                        <span class="preview-occurrence-index">Class ${i + 1} of ${weeksCount}</span>
                    </div>
                    `);
                }
                previewOccurrencesList.innerHTML = listItemsHtml.join('');
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

function openRescheduleModal(id, currentDateTime) {
    const [datePart, timePart] = currentDateTime.split('T');
    
    // Fallback parsing just in case
    let dateObj = new Date(datePart + 'T' + timePart);
    if (isNaN(dateObj.getTime())) {
        dateObj = new Date(currentDateTime);
    }
    const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
    document.getElementById('currentScheduledInput').value = dateObj.toLocaleString('en-US', options);

    document.getElementById('rescheduleForm').action = '/admin/bookings/' + id + '/reschedule';
    
    document.getElementById('reschDate').value = datePart;
    document.getElementById('reschTime').value = timePart;
    
    updateReschStartsAt();

    document.getElementById('rescheduleModal').classList.add('show');
}

function updateReschStartsAt() {
    const d = document.getElementById('reschDate').value;
    const t = document.getElementById('reschTime').value;
    if (d && t) {
        document.getElementById('reschStartsAt').value = d + 'T' + t;
    }
}

function populateReschTimeDropdown() {
    const select = document.getElementById('reschTime');
    if (!select) return;
    const options = [];
    for (let hour = 8; hour <= 26; hour++) {
        for (let min = 0; min < 60; min += 30) {
            if (hour === 26 && min > 0) break;
            const adjustedHour = hour % 24;
            const period = hour >= 24 || adjustedHour < 12 ? "AM" : "PM";
            let displayHour = adjustedHour % 12;
            if (displayHour === 0) displayHour = 12;
            const displayMin = String(min).padStart(2, '0');
            const timeStr = `${String(displayHour).padStart(2, '0')}:${displayMin} ${period}`;
            const valStr = `${String(adjustedHour).padStart(2, '0')}:${displayMin}`;
            options.push(`<option value="${valStr}">${timeStr}</option>`);
        }
    }
    select.innerHTML = options.join('');
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.remove('show');
}
</script>
@endpush
