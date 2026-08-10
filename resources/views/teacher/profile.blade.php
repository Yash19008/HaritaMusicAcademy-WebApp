@extends('layouts.main')
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
    <div class="profile-avatar-box" style="position: relative; display: inline-block;">
        <div class="avatar avatar-lg" id="profileInitials">{{ strtoupper(substr($teacher->name ?? 'T', 0, 1)) }}</div>
    </div>
    <div class="profile-meta">
        <h2 id="profileName">{{ $teacher->name ?? 'N/A' }}</h2>
        <p id="profileSub">Instructor at Harita Music Academy</p>
    </div>
</div>

<div class="profile-grid-layout">
    <div class="card">
        <div class="card-header">
            <h4 class="font-semibold">Professional Profile</h4>
        </div>
        <div class="card-body">
            <div class="info-list-item">
                <span class="text-muted">Primary Course</span>
                <span class="font-semibold">{{ $teacher->course->name ?? 'N/A' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Phone</span>
                <span class="font-semibold">{{ $teacher->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Email</span>
                <span class="font-semibold">{{ $teacher->email ?? 'N/A' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Status</span>
                <span class="font-semibold">{{ ucfirst($teacher->status ?? 'active') }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Per Class Rate</span>
                <span class="font-semibold">₹{{ $teacher->per_class_rate ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="font-semibold">Teacher Biography</h4>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                {{ $teacher->bio ?? 'No biography provided.' }}
            </p>
            <h4 class="font-semibold text-primary mb-2" style="font-size: 0.95rem;">Assigned Schedule Rules</h4>
            <div class="info-list-item">
                <span class="text-muted">Weekly Off</span>
                <span class="font-semibold">{{ $teacher->week_off ?? 'None' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
