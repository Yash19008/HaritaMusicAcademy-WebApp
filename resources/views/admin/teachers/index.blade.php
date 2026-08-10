@extends('layouts.main')
@section('title', 'Teacher Master')
@section('page', 'teachers')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .table-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--primary); color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; flex-shrink: 0; margin-right: .5rem;
  }
  .week-off-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: .25rem; padding: .5rem;
    height: 90px; overflow-y: auto;
    background: var(--bg-card);
  }
  .week-off-grid label {
    display: flex; align-items: center; gap: .25rem;
    font-size: 12.5px; font-weight: normal; margin: 0; cursor: pointer;
  }
</style>
@endpush

@section('content')

@if(session('success'))
  <div style="padding:.75rem 1rem;margin-bottom:1rem;background:#ecfdf5;color:#059669;border-radius:var(--radius-sm);font-size:13.5px;">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
  <div style="padding:.75rem 1rem;margin-bottom:1rem;background:#fef2f2;color:#dc2626;border-radius:var(--radius-sm);font-size:13.5px;">❌ {{ session('error') }}</div>
@endif

{{-- Filter / Actions --}}
<div class="card mb-3">
  <div class="card-body d-flex flex-wrap align-center justify-between gap-3">
    <div class="d-flex gap-2 flex-wrap" style="flex:1;max-width:400px;">
      <input type="text" id="searchBar" class="form-control" placeholder="Quick filter by name..." style="flex:1;"
             oninput="if(dtTable) dtTable.search(this.value).draw()">
    </div>
    <button class="btn btn-primary" onclick="openAddTeacher()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Add Teacher
    </button>
  </div>
</div>

{{-- Teachers Table --}}
<div class="card p-3" style="overflow-x:auto;">
  <table class="table display responsive nowrap" id="teachersTable" style="width:100%">
    <thead>
      <tr>
        <th data-priority="7">ID</th>
        <th data-priority="1">Teacher Name</th>
        <th data-priority="6">Email</th>
        <th data-priority="5">Phone</th>
        <th data-priority="3">Course</th>
        <th data-priority="4">Week Off</th>
        <th data-priority="2">Status</th>
        <th data-priority="1" style="width:80px;text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($teachers as $teacher)
      <tr>
        <td class="font-bold" style="color:var(--primary)">TCH{{ str_pad($teacher->id, 3, '0', STR_PAD_LEFT) }}</td>
        <td>
          <div class="d-flex align-center">
            <span class="table-avatar">{{ strtoupper(substr($teacher->name,0,1)) }}</span>
            <span class="font-semibold">{{ $teacher->name }}</span>
          </div>
        </td>
        <td>{{ $teacher->email }}</td>
        <td>{{ $teacher->phone ?? '—' }}</td>
        <td>
          @if($teacher->course)
            <span class="badge badge-primary">{{ $teacher->course->name }}</span>
          @else
            <span class="text-muted">—</span>
          @endif
        </td>
        <td>
          @if($teacher->week_off)
            <span class="badge badge-secondary" style="font-size:11px;">{{ $teacher->week_off }}</span>
          @else
            <span class="text-muted">None</span>
          @endif
        </td>
        <td>
          @php $st = strtolower($teacher->status ?? 'inactive'); @endphp
          <span class="badge {{ $st==='active' ? 'badge-success' : ($st==='on_leave' ? 'badge-warning' : 'badge-danger') }}">
            {{ $st === 'on_leave' ? 'On Leave' : ucfirst($teacher->status) }}
          </span>
        </td>
        <td>
          <div class="actions-dropdown-container">
            <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event,this)">⋮</button>
            <div class="actions-dropdown-menu" style="min-width:140px;">
              <button class="actions-dropdown-item" onclick="openEditTeacher({{ $teacher->id }})">✏️ Edit</button>
              <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Delete this teacher?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="actions-dropdown-item text-danger" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">🗑️ Delete</button>
              </form>
            </div>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" class="text-center py-4" style="color:var(--text-muted)">No teachers found. Add your first teacher above.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ═══ ADD / EDIT TEACHER MODAL ═══ --}}
<div id="teacherModal" class="modal-backdrop">
  <div class="modal" style="max-width:700px;">
    <div class="modal-header">
      <h3 id="teacherModalTitle" class="font-semibold text-serif">Add New Teacher</h3>
      <button class="modal-close" onclick="hideModal('teacherModal')">×</button>
    </div>
    <form id="teacherForm" method="POST" action="{{ route('admin.teachers.store') }}">
      @csrf
      <input type="hidden" name="_method" id="tfMethod" value="POST">
      <div class="modal-body">

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" id="tfName" class="form-control" required placeholder="e.g. Meera Sharma">
          </div>
          <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" id="tfEmail" class="form-control" required placeholder="e.g. meera@haritamusic.com">
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" id="tfPhone" class="form-control" placeholder="+91 87654 32109">
          </div>
          <div class="form-group">
            <label class="form-label">Experience</label>
            <input type="text" name="experience" id="tfExperience" class="form-control" placeholder="e.g. 8 Years">
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" id="tfSpecialization" class="form-control" placeholder="e.g. Hindustani Classical, Piano">
          </div>
          <div class="form-group">
            <label class="form-label">Music Category (Course)</label>
            <select name="course_id" id="tfCourse" class="form-control">
              <option value="">— Select Course —</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Joining Date</label>
            <input type="date" name="joining_date" id="tfJoiningDate" class="form-control">
          </div>
          <div class="form-group">
            <label class="form-label">Rating (0–5)</label>
            <input type="number" name="rating" id="tfRating" class="form-control" min="0" max="5" step="0.1" placeholder="e.g. 4.8">
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Emergency Contact Name</label>
            <input type="text" name="emergency_contact_name" id="tfEmgName" class="form-control" placeholder="e.g. Rajesh Sharma">
          </div>
          <div class="form-group">
            <label class="form-label">Emergency Contact Phone</label>
            <input type="text" name="emergency_contact_phone" id="tfEmgPhone" class="form-control" placeholder="+91 98765 43210">
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Level</label>
            <select name="level" id="tfLevel" class="form-control">
              <option value="Foundation Level">Foundation Level</option>
              <option value="Intermediate Level">Intermediate Level</option>
              <option value="Advanced Level">Advanced Level</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status *</label>
            <select name="status" id="tfStatus" class="form-control" required>
              <option value="active">Active</option>
              <option value="on_leave">On Leave</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Per Class Fee (₹)</label>
            <input type="number" name="per_class_rate" id="tfClassFee" class="form-control" step="0.01" min="0" placeholder="e.g. 500">
          </div>
          <div class="form-group">
            <label class="form-label">Certifications (comma-separated)</label>
            <input type="text" name="certifications" id="tfCertifications" class="form-control" placeholder="e.g. ABRSM Grade 8, RCM">
          </div>
        </div>

        <div class="grid grid-2 gap-3">
          <div class="form-group">
            <label class="form-label">Week Off Days</label>
            <div class="form-control week-off-grid">
              @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
              <label>
                <input type="checkbox" name="week_off[]" value="{{ $day }}" style="width:13px;height:13px;accent-color:var(--primary);">
                {{ $day }}
              </label>
              @endforeach
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">YouTube Video Link</label>
            <input type="text" name="youtube_url" id="tfYoutube" class="form-control" placeholder="https://youtube.com/watch?v=...">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Short Biography</label>
          <textarea name="bio" id="tfBio" class="form-control" style="height:60px;" placeholder="Teaching experience and background..."></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideModal('teacherModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Teacher</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
  let dtTable = null;

  document.addEventListener('DOMContentLoaded', function () {
    dtTable = setupDataTable('teachersTable');
  });

  @php
    $teachersJson = $teachers->map(function($t) {
        return [
            'id'                      => $t->id,
            'name'                    => $t->name,
            'email'                   => $t->email,
            'phone'                   => $t->phone ?? '',
            'course_id'               => $t->course_id ?? '',
            'experience'              => $t->experience ?? '',
            'specialization'          => $t->specialization ?? '',
            'joining_date'            => $t->joining_date ? \Carbon\Carbon::parse($t->joining_date)->format('Y-m-d') : '',
            'rating'                  => $t->rating ?? '',
            'emergency_contact_name'  => $t->emergency_contact_name ?? '',
            'emergency_contact_phone' => $t->emergency_contact_phone ?? '',
            'level'                   => $t->level ?? 'Foundation Level',
            'status'                  => $t->status ?? 'active',
            'per_class_rate'          => $t->per_class_rate ?? '',
            'certifications'          => $t->certifications ?? '',
            'week_off'                => $t->week_off ?? '',
            'youtube_url'             => $t->youtube_url ?? '',
            'bio'                     => $t->bio ?? '',
        ];
    })->values();
  @endphp
  const allTeachers = @json($teachersJson);

  function openAddTeacher() {
    document.getElementById('teacherForm').reset();
    document.querySelectorAll('input[name="week_off[]"]').forEach(cb => cb.checked = false);
    document.getElementById('tfMethod').value = 'POST';
    document.getElementById('teacherForm').action = '{{ route("admin.teachers.store") }}';
    document.getElementById('teacherModalTitle').textContent = 'Add New Teacher';
    showModal('teacherModal');
  }

  function openEditTeacher(id) {
    const t = allTeachers.find(x => x.id === id);
    if (!t) return;

    document.getElementById('tfName').value           = t.name;
    document.getElementById('tfEmail').value          = t.email;
    document.getElementById('tfPhone').value          = t.phone;
    document.getElementById('tfCourse').value         = t.course_id;
    document.getElementById('tfExperience').value     = t.experience;
    document.getElementById('tfSpecialization').value = t.specialization;
    document.getElementById('tfJoiningDate').value    = t.joining_date;
    document.getElementById('tfRating').value         = t.rating;
    document.getElementById('tfEmgName').value        = t.emergency_contact_name;
    document.getElementById('tfEmgPhone').value       = t.emergency_contact_phone;
    document.getElementById('tfLevel').value          = t.level;
    document.getElementById('tfStatus').value         = t.status;
    document.getElementById('tfClassFee').value       = t.per_class_rate;
    document.getElementById('tfCertifications').value = t.certifications;
    document.getElementById('tfYoutube').value        = t.youtube_url;
    document.getElementById('tfBio').value            = t.bio;

    // Tick week-off checkboxes
    const weekDays = t.week_off ? t.week_off.split(',').map(d => d.trim()) : [];
    document.querySelectorAll('input[name="week_off[]"]').forEach(cb => {
      cb.checked = weekDays.includes(cb.value);
    });

    document.getElementById('tfMethod').value = 'PUT';
    document.getElementById('teacherForm').action = '{{ url("admin/teachers") }}/' + id;
    document.getElementById('teacherModalTitle').textContent = 'Edit Teacher Details';
    showModal('teacherModal');
  }
</script>
@endpush
