@extends('layouts.main')
@section('title', 'My Profile')
@section('page', 'profile')

@push('styles')
<style>
  .profile-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 2rem 1.5rem;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    color: var(--text-white);
    margin-bottom: 1.5rem;
    border: 1px solid var(--primary-light);
  }

  @media (max-width: 768px) {
    .profile-hero {
      flex-direction: column;
      text-align: center;
      padding: 1.5rem 1rem;
    }
  }

  .profile-avatar-box .avatar-lg {
    border: 3px solid var(--secondary);
    background-color: var(--bg-card);
    color: var(--primary);
    box-shadow: var(--shadow-lg);
  }

  .profile-meta h2 {
    color: var(--secondary-light);
    font-size: 1.6rem;
    margin-bottom: 0.25rem;
  }

  .profile-meta p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.9rem;
  }

  .profile-tabs-container {
    display: flex;
    gap: 0.5rem;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    padding-bottom: 0.25rem;
  }

  .profile-tab {
    background: transparent;
    border: none;
    padding: 0.5rem 1.25rem;
    cursor: pointer;
    font-weight: 600;
    color: var(--text-muted);
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
  }

  .profile-tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
  }

  .profile-grid-layout {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1.25rem;
    animation: fadeIn var(--transition-speed);
  }

  @media (max-width: 992px) {
    .profile-grid-layout {
      grid-template-columns: 1fr;
    }
  }

  .info-list-item {
    display: flex;
    justify-content: space-between;
    padding: 0.65rem 0;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.85rem;
  }

  .info-list-item:last-child {
    border-bottom: none;
  }
</style>
@endpush

@section('content')

<!-- HERO -->
<div class="profile-hero slide-up">
  <div class="profile-avatar-box">
    <div class="avatar avatar-lg" id="profileInitials">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
  </div>
  <div class="profile-meta">
    <h2 id="profileName">{{ $user->name }}</h2>
    <p id="profileSub">System {{ $user->role ? ucfirst($user->role) : 'Administrator' }} | Harita Music Academy</p>
  </div>
</div>

<!-- Tab Buttons -->
<div class="profile-tabs-container">
  <button class="profile-tab active" id="tab_admin" onclick="switchProfileTab('admin')">Admin Profile</button>
  @if($user->teacher)
    <button class="profile-tab" id="tab_teacher" onclick="switchProfileTab('teacher')">Teacher Profile</button>
  @endif
  @if($user->student)
    <button class="profile-tab" id="tab_student" onclick="switchProfileTab('student')">Student Profile</button>
  @endif
</div>

<!-- ==========================================
      ADMIN PROFILE VIEW
      ========================================== -->
<div id="adminProfileView" class="profile-grid-layout tab-content">
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Security Credentials</h4>
    </div>
    <div class="card-body">
      <div class="info-list-item">
        <span class="text-muted">Name</span>
        <span class="font-semibold">{{ $user->name }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Contact Email</span>
        <span class="font-semibold">{{ $user->email }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">System Level</span>
        <span class="font-semibold text-primary">Full Administrator</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Account Status</span>
        <span class="font-semibold text-success">{{ ucfirst($user->status ?? 'Active') }}</span>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Account Information</h4>
    </div>
    <div class="card-body">
      <p class="text-muted mb-4" style="font-size:0.9rem;">
        You are currently logged in with Administrative privileges. From the panel, you have full access to manage students, teachers, credits, and system settings.
      </p>
      <div class="info-list-item">
        <span class="text-muted">Member Since</span>
        <span class="font-semibold">{{ $user->created_at->format('F d, Y') }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Last Updated</span>
        <span class="font-semibold">{{ $user->updated_at->diffForHumans() }}</span>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
      TEACHER PROFILE VIEW
      ========================================== -->
@if($user->teacher)
<div id="teacherProfileView" class="profile-grid-layout tab-content" style="display:none;">
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Academic Profile</h4>
    </div>
    <div class="card-body">
      <div class="info-list-item">
        <span class="text-muted">Course</span>
        <span class="font-semibold">{{ $user->teacher->course->name ?? 'None' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Email</span>
        <span class="font-semibold">{{ $user->teacher->email }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Phone</span>
        <span class="font-semibold">{{ $user->teacher->phone }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Per Class Fee</span>
        <span class="font-semibold">₹{{ $user->teacher->per_class_rate }}</span>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Teacher Biography</h4>
    </div>
    <div class="card-body">
      <p class="text-muted mb-4">
        {{ $user->teacher->bio ?: 'No biography added yet.' }}
      </p>
      <h4 class="font-semibold text-primary mb-2" style="font-size: 0.95rem;">Additional Information</h4>
      <div class="info-list-item">
        <span class="text-muted">Experience</span>
        <span class="font-semibold">{{ $user->teacher->experience ?: 'N/A' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Certifications</span>
        <span class="font-semibold">{{ $user->teacher->certifications ?: 'N/A' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Week Offs</span>
        <span class="font-semibold">{{ $user->teacher->week_off ?: 'None' }}</span>
      </div>
    </div>
  </div>
</div>
@endif

<!-- ==========================================
      STUDENT PROFILE VIEW
      ========================================== -->
@if($user->student)
<div id="studentProfileView" class="profile-grid-layout tab-content" style="{{ ($user->role === 'admin' || $user->teacher) ? 'display:none;' : '' }}">
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Enrolment Overview</h4>
    </div>
    <div class="card-body">
      <div class="info-list-item">
        <span class="text-muted">Primary Course</span>
        <span class="font-semibold">{{ $user->student->course->name ?? 'None' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Assigned Mentor</span>
        <span class="font-semibold">{{ $user->student->teacher->name ?? 'Unassigned' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Remaining Credits</span>
        <span class="font-semibold text-primary">{{ $user->student->credits }} Class Credits</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Admission Date</span>
        <span class="font-semibold">{{ $user->student->joining_date ? \Carbon\Carbon::parse($user->student->joining_date)->format('F d, Y') : 'N/A' }}</span>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h4 class="font-semibold">Student Information</h4>
    </div>
    <div class="card-body">
      <div class="info-list-item">
        <span class="text-muted">Date of Birth</span>
        <span class="font-semibold">{{ $user->student->dob ? \Carbon\Carbon::parse($user->student->dob)->format('M d, Y') : 'N/A' }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">Gender</span>
        <span class="font-semibold">{{ ucfirst($user->student->gender ?? 'N/A') }}</span>
      </div>
      <div class="info-list-item">
        <span class="text-muted">City / Location</span>
        <span class="font-semibold">{{ $user->student->city ?? 'N/A' }}, {{ $user->student->country ?? 'N/A' }}</span>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@push('scripts')
<script>
  function switchProfileTab(roleName) {
    // Toggle tab buttons class
    document.querySelectorAll(".profile-tab").forEach(tab => {
      if (tab.id === `tab_${roleName}`) {
        tab.classList.add("active");
      } else {
        tab.classList.remove("active");
      }
    });

    // Toggle profile views
    document.querySelectorAll(".tab-content").forEach(view => {
      view.style.display = "none";
    });
    const targetView = document.getElementById(`${roleName}ProfileView`);
    if(targetView) {
      targetView.style.display = "";
    }
  }
</script>
@endpush
