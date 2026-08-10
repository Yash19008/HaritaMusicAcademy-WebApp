@extends('layouts.main')
@section('title', 'Class Booking')
@section('page', 'class-booking')

@push('styles')
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
</style>
@endpush

@section('content')

      <!-- BOOKING PANEL (Admins & Teachers) -->
      <div class="card mb-4" id="classBookingSection">
        <div class="card-header">
          <h4 class="font-semibold" id="bookingPanelTitle">Schedule a New Class Room</h4>
        </div>
        <form id="bookingForm" method="POST" action="{{ route('admin.bookings.store') }}" class="card-body" onsubmit="prepareBookingSubmit(event)">
          @csrf
          <input type="hidden" name="teacher_id" id="hiddenTeacherId">
          <input type="hidden" name="instrument" id="hiddenInstrument">
          <input type="hidden" name="starts_at" id="hiddenStartsAt">
          <input type="hidden" name="ends_at" id="hiddenEndsAt">
          <input type="hidden" name="type" id="hiddenType">
          <div class="booking-container">
            <!-- Left Side: Inputs -->
            <div class="booking-form-section">
              <div class="booking-grid">
                <div class="form-group">
                  <label class="form-label" for="bookStudent">Student Name</label>
                  <select name="student_id" id="bookStudent" class="form-control" required onchange="onStudentSelectChange()">
                    <!-- Loaded dynamically -->
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="bookDate">Select Date</label>
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
                  <label class="form-label" for="bookTime">Start Time</label>
                  <select id="bookTime" class="form-control" required onchange="onTimeSelectChange()">
                    <!-- Loaded dynamically from 8 AM to 2 AM -->
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Call Duration</label>
                  <input type="text" class="form-control" value="40 Minutes" readonly style="background-color: var(--border-light)">
                </div>
              </div>

              <!-- Recurrence Settings (Initially hidden until date selected) -->
              <div id="recurrenceSection" style="display: none; flex-direction: column; gap: 1rem;">
                <div class="form-group">
                  <label class="form-label">Booking Type</label>
                  <div class="segmented-control">
                    <button type="button" id="btnOneTime" class="segmented-control-btn active" onclick="setRecurrenceMode('one-time')">One-time Class</button>
                    <button type="button" id="btnRecurring" class="segmented-control-btn" onclick="setRecurrenceMode('recurring')">Recurring Weekly</button>
                  </div>
                  <input type="hidden" id="recurrenceMode" value="one-time">
                </div>

                <div id="recurringWeeksGroup" class="form-group" style="display: none;">
                  <label class="form-label" for="bookWeeks">Number of Weekly Occurrences</label>
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

              <button type="submit" id="btnSubmitBooking" class="btn btn-primary" style="margin-top: 1rem;">Create Scheduled Class Room</button>
            </div>

            <!-- Right Side: Live Summary & Preview -->
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
                  <span id="previewMeet" class="preview-val meet-badge">Auto-Generated</span>
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
        </form>
      </div>

      

      <!-- Student view warning helper -->
      <div class="card grid-2-col-span-2" style="grid-column: span 2; display:none;" id="studentBookingPlaceholder">
        <div class="card-body text-center p-4">
          <span style="font-size: 2.5rem;">🎻</span>
          <h3 class="text-serif text-primary mt-2">Class Booking Matrix</h3>
          <p class="text-muted mt-2">Class slots are assigned by your mentor (Meera Sharma). If you need to request a
            slot, please email the administrator or choose one of your active classes on the right to trigger a
            reschedule call.</p>
          <button class="btn btn-primary mt-3" onclick="window.location.href='profile.html'">View Teacher
            Contact</button>
        </div>
      </div>

      <!-- ACTIVE SCHEDULE FOR RESCHEDULING (All Roles) -->
      <div class="card mb-4">
        <div class="card-header">
          <h4 class="font-semibold">Active Scheduled Classes</h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="rescheduleTable" style="width: 100%;">
            <thead>
              <tr>
                <th>Instrument</th>
                <th>Date & Time</th>
                <th id="partyHeader">With</th>
                <th>Google Meet</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="rescheduleTableBody">
              <!-- Populated via JS -->
            </tbody>
          </table>
        </div>
      </div>

      
@endsection

@push('scripts')
<script>
    const serverStudents = @json($students->map(function($s) {
        return [
            'id' => (string) $s->id,
            'name' => $s->name,
            'teacher' => $s->teacher->name ?? 'N/A',
            'teacher_id' => $s->teacher_id ?? '',
            'instrument' => $s->course->name ?? 'N/A',
            'credits' => $s->credits ?? 0,
            'status' => 'Active'
        ];
    })->values());

    const serverBookings = @json($activeBookings->map(function($b) {
        return [
            'id' => (string) $b->id,
            'instrument' => $b->instrument,
            'dateTime' => \Carbon\Carbon::parse($b->starts_at)->format('Y-m-d\TH:i:s'),
            'duration' => $b->duration_minutes . ' mins',
            'studentName' => $b->student->name ?? 'N/A',
            'teacherName' => $b->teacher->name ?? 'N/A',
            'meetLink' => $b->meet_link,
            'status' => ucfirst($b->status),
            'recurrence' => $b->type === 'recurring' ? 'Recurring' : null,
        ];
    })->values());

let dtActiveClasses = null;
    const DAYS_OF_WEEK = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    document.addEventListener("DOMContentLoaded", () => {
      populateStudentSelect();
      populateTimeDropdown();
      loadActiveClasses();
      applyRoleBookingVisibility();
    });

    function onRoleChange() {
      populateStudentSelect();
      loadActiveClasses();
      applyRoleBookingVisibility();
    }

    function applyRoleBookingVisibility() {
      const role = db.getCurrentRole();
      const formSection = document.getElementById("classBookingSection");
      const placeholder = document.getElementById("studentBookingPlaceholder");

      if (role === 'student') {
        formSection.style.display = "none";
        placeholder.style.display = "block";
      } else {
        formSection.style.display = "block";
        placeholder.style.display = "none";
      }
    }

    function populateStudentSelect() {
      const students = serverStudents;
      const role = db.getCurrentRole();
      const select = document.getElementById("bookStudent");
      if (!select) return;

      const displayStudents = students.filter(s => {
        if (role === 'teacher' && s.teacher !== "Meera Sharma") return false;
        return s.status === 'Active';
      });

      select.innerHTML = '<option value="">-- Select Student --</option>' + 
        displayStudents.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
      
      onStudentSelectChange();
    }

    function onStudentSelectChange() {
      const select = document.getElementById("bookStudent");
      if (!select) return;
      const studentId = select.value;
      const students = serverStudents;
      const student = students.find(s => s.id === studentId);

      const statusTeacher = document.getElementById("statusTeacher");
      const statusInstrument = document.getElementById("statusInstrument");
      const statusCredits = document.getElementById("statusCredits");
      const studentStatusCard = document.getElementById("studentStatusCard");
      const bookWeeks = document.getElementById("bookWeeks");
      const warningText = document.getElementById("bookingWarningText");
      const submitBtn = document.getElementById("btnSubmitBooking");

      if (student) {
        statusTeacher.textContent = student.teacher;
        statusInstrument.textContent = student.instrument;
        statusCredits.textContent = `${student.credits} Class${student.credits === 1 ? '' : 'es'}`;
        studentStatusCard.style.display = "flex";

        if (student.credits <= 0) {
          warningText.textContent = `⚠️ Cannot book: student has 0 remaining class credits! Please purchase packages first.`;
          warningText.style.display = "block";
          submitBtn.disabled = true;
          document.getElementById("recurrenceSection").style.display = "none";
          bookWeeks.innerHTML = "";
        } else {
          warningText.style.display = "none";
          submitBtn.disabled = false;

          let weeksOptions = [];
          const maxWeeks = student.credits;
          for (let i = 1; i <= maxWeeks; i++) {
            weeksOptions.push(`<option value="${i}">${i} Week${i === 1 ? '' : 's'} (${i} Credit${i === 1 ? '' : 's'})</option>`);
          }
          bookWeeks.innerHTML = weeksOptions.join('');
          bookWeeks.value = Math.min(maxWeeks, 4);
        }
      } else {
        if (studentStatusCard) studentStatusCard.style.display = "none";
        if (warningText) warningText.style.display = "none";
        if (submitBtn) submitBtn.disabled = false;
        document.getElementById("recurrenceSection").style.display = "none";
      }

      updateLivePreview();
    }

    function populateTimeDropdown() {
      const select = document.getElementById("bookTime");
      const reschSelect = document.getElementById("reschNewTime");
      if (!select) return;

      const options = [];
      // Generate times from 8:00 AM to 2:00 AM (next day)
      // 8 to 26 (inclusive) where 24 = 12 AM, 25 = 1 AM, 26 = 2 AM
      for (let hour = 8; hour <= 26; hour++) {
        for (let min = 0; min < 60; min += 30) {
          if (hour === 26 && min > 0) break; // cap at 2:00 AM

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
      if (reschSelect) {
        reschSelect.innerHTML = options.join('');
      }
      onTimeSelectChange();
    }

    function getEndTime(startTimeVal) {
      if (!startTimeVal) return "";
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

    function onDateSelectChange() {
      const dateInput = document.getElementById("bookDate");
      const recurrenceSection = document.getElementById("recurrenceSection");
      const detectedDayLabel = document.getElementById("detectedDayLabel");

      if (!dateInput || !dateInput.value) {
        recurrenceSection.style.display = "none";
        return;
      }

      // Split date to avoid timezone offset shifts
      const [year, month, day] = dateInput.value.split('-').map(num => parseInt(num, 10));
      const dateObj = new Date(year, month - 1, day);
      const dayName = DAYS_OF_WEEK[dateObj.getDay()];

      detectedDayLabel.textContent = dayName;
      recurrenceSection.style.display = "flex";

      updateLivePreview();
    }

    function setRecurrenceMode(mode) {
      const btnOneTime = document.getElementById("btnOneTime");
      const btnRecurring = document.getElementById("btnRecurring");
      const recurringWeeksGroup = document.getElementById("recurringWeeksGroup");
      const recurrenceMode = document.getElementById("recurrenceMode");

      recurrenceMode.value = mode;

      if (mode === "one-time") {
        btnOneTime.classList.add("active");
        btnRecurring.classList.remove("active");
        recurringWeeksGroup.style.display = "none";
      } else {
        btnOneTime.classList.remove("active");
        btnRecurring.classList.add("active");
        recurringWeeksGroup.style.display = "block";
      }

      updateLivePreview();
    }

    function onTimeSelectChange() {
      updateLivePreview();
    }

    function onWeeksSelectChange() {
      updateLivePreview();
    }

    function generateMeetLink() {
      const chars = 'abcdefghijklmnopqrstuvwxyz';
      const part1 = Array.from({ length: 3 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
      const part2 = Array.from({ length: 4 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
      const part3 = Array.from({ length: 3 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
      return `https://meet.google.com/${part1}-${part2}-${part3}`;
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

      // Defaults
      previewStudent.textContent = "-";
      previewTeacher.textContent = "-";
      previewType.textContent = "-";
      previewStart.textContent = "-";
      previewEnd.textContent = "-";
      previewOccurrencesGroup.style.display = "none";
      previewOccurrencesList.innerHTML = "";

      if (!bookStudent || !bookStudent.value) return;

      const students = serverStudents;
      const student = students.find(s => s.id === bookStudent.value);
      if (!student) return;

      previewStudent.textContent = student.name;
      previewTeacher.textContent = student.teacher;

      let timeStr = "-";
      let endTimeStr = "-";
      if (bookTime && bookTime.value) {
        const selectedOpt = bookTime.options[bookTime.selectedIndex];
        timeStr = selectedOpt ? selectedOpt.text : "-";
        endTimeStr = getEndTime(bookTime.value);
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

    function loadActiveClasses() {
      const classes = serverBookings;
      const role = db.getCurrentRole();
      const container = document.getElementById("rescheduleTableBody");
      if (!container) return;

      if (dtActiveClasses) {
        dtActiveClasses.destroy();
      }
      container.innerHTML = "";

      const activeClasses = classes.filter(cls => {
        if (cls.status !== "Scheduled" && cls.status !== "Reschedule Requested") return false;
        // In real backend, these are already filtered if needed, but we do client-side filter just in case based on role
        if (role === 'teacher' && cls.teacherName !== "Meera Sharma") return false;
        if (role === 'student' && cls.studentName !== "Ananya Iyer") return false;
        return true;
      });

      const partyHeader = document.getElementById("partyHeader");
      if (partyHeader) {
        partyHeader.textContent = role === 'student' ? 'Teacher' : 'Student';
      }

      activeClasses.forEach(cls => {
        const dateObj = new Date(cls.dateTime);
        const formatOptions = { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
        const dateStr = dateObj.toLocaleDateString('en-US', formatOptions);
        const otherParty = role === 'student' ? cls.teacherName : cls.studentName;

        const meetLinkHtml = cls.meetLink
          ? `<a href="${cls.meetLink}" target="_blank" class="meet-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
              Join Meet
             </a>`
          : `<span class="text-muted" style="font-size: 0.75rem;">N/A</span>`;

        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td class="font-semibold text-primary">
            ${cls.instrument}
            ${cls.recurrence ? `<span style="display: block; font-size: 0.65rem; color: var(--text-light); font-weight: normal; margin-top: 2px;">${cls.recurrence}</span>` : ''}
          </td>
          <td class="font-medium">${dateStr} <span class="text-muted" style="font-size: 0.7rem; font-weight: normal;">(${cls.duration || '40 mins'})</span></td>
          <td>${otherParty}</td>
          <td>${meetLinkHtml}</td>
          <td>
            <button class="btn btn-secondary btn-sm" onclick="openRescheduleModal('${cls.id}')">Request Reschedule</button>
          </td>
        `;
        container.appendChild(tr);
      });

      dtActiveClasses = setupDataTable("rescheduleTable");
      applyRoleBookingVisibility();
    }

    function prepareBookingSubmit(e) {
      const studentId = document.getElementById("bookStudent").value;
      const bookDate = document.getElementById("bookDate").value;
      const bookTime = document.getElementById("bookTime").value;
      const recurrenceMode = document.getElementById("recurrenceMode").value;
      const bookWeeksVal = document.getElementById("bookWeeks").value;

      if (!studentId || !bookDate || !bookTime) {
        e.preventDefault();
        alert("Please fill in all required fields.");
        return;
      }

      const student = serverStudents.find(s => s.id === studentId);
      if (!student) {
        e.preventDefault();
        return;
      }

      const isRecurring = recurrenceMode === "recurring";
      const weeksCount = isRecurring ? (parseInt(bookWeeksVal, 10) || 1) : 1;

      if (student.credits < weeksCount) {
        e.preventDefault();
        alert(`Insufficient credits! This booking requires ${weeksCount} credit(s), but student only has ${student.credits} remaining.`);
        return;
      }

      document.getElementById("hiddenTeacherId").value = student.teacher_id;
      document.getElementById("hiddenInstrument").value = student.instrument;
      
      const [year, month, day] = bookDate.split('-').map(num => parseInt(num, 10));
      const [hStr, mStr] = bookTime.split(':');
      const h = parseInt(hStr, 10);
      const m = parseInt(mStr, 10);
      
      const startsAt = new Date(year, month - 1, day, h, m);
      const endsAt = new Date(startsAt.getTime() + 40 * 60000); 

      const formatYmdHis = (d) => {
        return d.getFullYear() + '-' + 
          String(d.getMonth() + 1).padStart(2, '0') + '-' + 
          String(d.getDate()).padStart(2, '0') + ' ' + 
          String(d.getHours()).padStart(2, '0') + ':' + 
          String(d.getMinutes()).padStart(2, '0') + ':00';
      };

      document.getElementById("hiddenStartsAt").value = formatYmdHis(startsAt);
      document.getElementById("hiddenEndsAt").value = formatYmdHis(endsAt);
      document.getElementById("hiddenType").value = isRecurring ? 'recurring' : 'one-time';
    }

    function openRescheduleModal(classId) {
      const classes = serverBookings;
      const cls = classes.find(c => c.id === classId);
      if (!cls) return;

      document.getElementById("reschClassId").value = cls.id;

      const dateObj = new Date(cls.dateTime);
      document.getElementById("reschCurrentDate").value = dateObj.toLocaleString();

      // Pre-populate proposed date
      const y = dateObj.getFullYear();
      const mo = String(dateObj.getMonth() + 1).padStart(2, '0');
      const d = String(dateObj.getDate()).padStart(2, '0');
      document.getElementById("reschNewDate").value = `${y}-${mo}-${d}`;

      // Pre-populate proposed time
      const hh = String(dateObj.getHours()).padStart(2, '0');
      const mm = String(dateObj.getMinutes()).padStart(2, '0');
      const timeVal = `${hh}:${mm}`;

      const reschTimeInput = document.getElementById("reschNewTime");
      if (reschTimeInput) {
        reschTimeInput.value = timeVal;
        if (!reschTimeInput.value) {
          const nearestMin = parseInt(mm, 10) >= 30 ? "30" : "00";
          reschTimeInput.value = `${hh}:${nearestMin}`;
        }
      }

      showModal("rescheduleModal");
    }

    function submitReschedule(e) {
      e.preventDefault();
      const classId = document.getElementById("reschClassId").value;
      const newProposedDate = document.getElementById("reschNewDate").value;
      const newProposedTime = document.getElementById("reschNewTime").value;

      const classes = serverBookings;
      const idx = classes.findIndex(c => c.id === classId);
      if (idx === -1) return;

      const combinedDateTime = `${newProposedDate}T${newProposedTime}`;

      classes[idx].status = "Reschedule Requested";
      classes[idx].tempDateTime = combinedDateTime;
      classes[idx].rescheduledBy = db.getCurrentRole();

      // db.setClasses(classes);
      hideModal("rescheduleModal");
      loadActiveClasses();
      alert("Reschedule request submitted successfully! Awaiting review.");
    }
</script>
@endpush
