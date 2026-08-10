@extends('layouts.main')
@section('title', 'My Classes')
@section('page', 'my-classes')

@push('styles')
<style>
.agenda-item {
      border-left: 5px solid var(--primary);
      padding: 1rem 1.25rem;
      background-color: var(--bg-card);
      margin-bottom: 0.75rem;
      border-radius: 0 var(--radius-md) var(--radius-md) 0;
      border-top: 1px solid var(--border-color);
      border-right: 1px solid var(--border-color);
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.2s;
    }

    .agenda-item:hover {
      transform: translateX(3px);
      box-shadow: var(--shadow-sm);
    }

    .agenda-item.completed {
      border-left-color: var(--success);
      opacity: 0.8;
    }

    .agenda-item.reschedule-requested {
      border-left-color: var(--warning);
    }

    .agenda-date-badge {
      background-color: var(--border-light);
      padding: 0.4rem 0.85rem;
      border-radius: var(--radius-sm);
      text-align: center;
      min-width: 70px;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div style="background: var(--success-bg); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
        <div class="d-flex gap-2" style="flex: 1; max-width: 400px;">
            <select id="statusFilter" class="form-control" onchange="filterClasses()">
                <option value="">All Classes</option>
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="reschedule_requested">Reschedule Requested</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="openBookModal()">
            Book New Session
        </button>
    </div>
</div>

<div id="agendaList" class="slide-up">
    @forelse($bookings as $booking)
        @php
            $customClass = "";
            $badgeClass = "badge-primary";
            $statusLabel = ucfirst($booking->status);

            if ($booking->status === "completed") {
                $customClass = "completed";
                $badgeClass = "badge-success";
            } elseif ($booking->status === "reschedule_requested") {
                $customClass = "reschedule-requested";
                $badgeClass = "badge-warning";
                $statusLabel = "Reschedule Requested";
            } elseif ($booking->status === "cancelled") {
                $badgeClass = "badge-danger";
            }
        @endphp

        <div class="agenda-item {{ $customClass }}" data-status="{{ $booking->status }}">
            <div class="d-flex align-center gap-3">
                <div class="agenda-date-badge">
                    <div class="font-bold text-primary" style="font-size: 1.2rem;">{{ $booking->starts_at->format('d') }}</div>
                    <div class="text-muted font-bold" style="font-size: 0.7rem;">{{ strtoupper($booking->starts_at->format('M')) }}</div>
                </div>
                <div>
                    <h4 class="font-semibold text-primary">{{ $booking->instrument }}</h4>
                    <div class="text-muted" style="font-size: 0.8rem;">
                        <strong>{{ $booking->starts_at->format('h:i A') }}</strong> ({{ $booking->duration_minutes }} mins) | 
                        Teacher: <a href="javascript:void(0)" class="text-primary hover-underline font-semibold" onclick="showTeacherProfileModal('{{ $booking->teacher->user->name ?? 'N/A' }}', '{{ implode(', ', $booking->teacher->instruments ?? []) }}')">{{ $booking->teacher->user->name ?? 'N/A' }}</a>
                    </div>
                </div>
            </div>
            <div>
                @if($booking->status === 'scheduled')
                    <div class="d-flex gap-2">
                        <a href="{{ $booking->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="btn btn-primary btn-sm" onclick="alert('The call is recorded for quality purpose.')" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Join Class</a>
                        <button class="btn btn-secondary btn-sm" onclick="openRescheduleModal({{ $booking->id }})">Reschedule</button>
                    </div>
                @elseif($booking->status === 'reschedule_requested')
                    <span class="badge badge-warning">Awaiting Approval</span>
                @else
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                @endif
            </div>
        </div>
    @empty
        <div class="card p-4 text-center text-muted">No classes scheduled.</div>
    @endforelse
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="modal-backdrop">
    <div class="modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="font-semibold">Request Reschedule</h3>
            <button class="modal-close" onclick="closeRescheduleModal()">×</button>
        </div>
        <form id="rescheduleForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div style="border: 1px solid #51040e; border-left: 4px solid #51040e; background-color: rgba(81, 4, 14, 0.04); color: #51040e; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.8rem;">
                    <h4 class="font-bold" style="margin-bottom: 0.4rem; color: #51040e; display: flex; align-items: center; gap: 0.35rem;">
                        <span>⚠️</span> Rescheduling Policy
                    </h4>
                    <ul style="padding-left: 1.2rem; margin: 0; list-style-type: disc;">
                        <li style="margin-bottom: 0.25rem;">National Students can reschedule a class up to 10 hours before the scheduled class.</li>
                        <li style="margin-bottom: 0.25rem;">International Students can reschedule a class up to 12 hours before the scheduled class.</li>
                        <li style="margin-bottom: 0.25rem;">Maximum 2 reschedules per calendar month are allowed.</li>
                        <li>Requests made after the permitted time will be automatically rejected.</li>
                    </ul>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="rescheduleDate">Proposed Date</label>
                    <input type="date" id="rescheduleDate" name="reschedule_date" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="rescheduleTimeSlot">Available Slots</label>
                    <input type="time" name="reschedule_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rescheduleReason">Reason for Reschedule</label>
                    <textarea id="rescheduleReason" name="reschedule_reason" class="form-control" rows="3" placeholder="Explain the reason for request..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<!-- Book New Session Modal -->
<div id="bookSessionModal" class="modal-backdrop">
    <div class="modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="font-semibold">Book New Session</h3>
            <button class="modal-close" onclick="closeBookModal()">×</button>
        </div>
        <form method="POST" action="{{ route('student.my-classes.book') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label" for="bookInstrument">Select Instrument / Class Type</label>
                    <select name="instrument" id="bookInstrument" class="form-control" required>
                        <option value="">-- Select Instrument --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->name }}" {{ (isset($student->course) && $student->course->name === $course->name) ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="bookTeacher">Select Instructor</label>
                    <select id="bookTeacher" name="teacher_id" class="form-control" required>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->user->name }} ({{ implode(', ', $t->instruments ?? []) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="bookDateTime">Choose Date & Time</label>
                    <input type="datetime-local" id="bookDateTime" name="starts_at" class="form-control" required>
                </div>
                <div class="mb-2" style="font-size: 13px; color: var(--text-muted);">
                    🪙 Booking will deduct <strong>1 Class Credit</strong>. Your balance: <strong>{{ $student->credits ?? 0 }}</strong> credits.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBookModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- Teacher Profile Modal -->
<div id="teacherProfileModal" class="modal-backdrop">
    <div class="modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3 class="font-semibold text-serif">Mentor Biography</h3>
            <button class="modal-close" onclick="closeTeacherProfileModal()">×</button>
        </div>
        <div class="modal-body p-4" id="teacherProfileModalBody">
            <div class="text-center mb-3">
                <div class="avatar avatar-lg mx-auto" style="width: 70px; height: 70px; font-size: 1.5rem; line-height: 70px; border-radius: 50%; background: var(--primary-light); color: var(--text-main); font-weight: bold; margin-bottom: 0.5rem; border: 2.5px solid var(--primary); display: flex; align-items: center; justify-content: center; margin-left: auto !important; margin-right: auto !important; float: none !important;" id="teacherAvatar">
                    T
                </div>
                <h3 class="font-bold text-serif" style="font-size: 1.35rem; margin-bottom: 0.25rem;" id="teacherName">Name</h3>
                <span class="badge badge-success" style="font-size: 0.75rem;">Academy Mentor</span>
            </div>
            <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
                <span class="text-muted">Specialization</span>
                <span class="font-bold" id="teacherSpecialization">Vocal</span>
            </div>
            <button class="btn btn-secondary w-100 mt-4" onclick="closeTeacherProfileModal()">Close Bio</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterClasses() {
        const filter = document.getElementById('statusFilter').value;
        const items = document.querySelectorAll('.agenda-item');
        
        items.forEach(item => {
            if (filter === "" || item.getAttribute('data-status') === filter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function openBookModal() {
        document.getElementById('bookSessionModal').classList.add('show');
    }

    function closeBookModal() {
        document.getElementById('bookSessionModal').classList.remove('show');
    }

    function openRescheduleModal(id) {
        document.getElementById('rescheduleForm').action = `/student/my-classes/${id}/reschedule`;
        document.getElementById('rescheduleModal').classList.add('show');
    }

    function closeRescheduleModal() {
        document.getElementById('rescheduleModal').classList.remove('show');
    }

    function showTeacherProfileModal(name, specs) {
        document.getElementById('teacherName').innerText = name;
        document.getElementById('teacherAvatar').innerText = name.charAt(0);
        document.getElementById('teacherSpecialization').innerText = specs || 'Vocal';
        document.getElementById('teacherProfileModal').classList.add('show');
    }

    function closeTeacherProfileModal() {
        document.getElementById('teacherProfileModal').classList.remove('show');
    }

    // Global close event for clicking overlay backdrop
    window.addEventListener("click", (e) => {
      const resModal = document.getElementById("rescheduleModal");
      const bookModal = document.getElementById("bookSessionModal");
      const teachModal = document.getElementById("teacherProfileModal");
      if (e.target === resModal) closeRescheduleModal();
      if (e.target === bookModal) closeBookModal();
      if (e.target === teachModal) closeTeacherProfileModal();
    });
</script>
@endpush
