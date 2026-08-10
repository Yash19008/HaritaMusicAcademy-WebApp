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
              @forelse($todayClasses as $class)
              <div class="student-class-box">
                  <div class="d-flex justify-between align-center mb-2">
                    <span class="badge badge-primary">{{ $class->starts_at->format('h:i A') }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">{{ $class->duration_minutes }} mins</span>
                  </div>
                  <div>
                    <h5 class="font-bold text-main" style="margin-bottom: 0.25rem;">{{ $class->student->user->name ?? 'N/A' }}</h5>
                    <p class="text-muted" style="font-size: 0.85rem;">{{ $class->instrument }}</p>
                  </div>
                  <div class="d-flex gap-2 mt-2">
                    <a href="{{ $class->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="btn btn-primary btn-sm" style="flex:1; text-align:center; text-decoration:none;">Start Class</a>
                  </div>
              </div>
              @empty
              <p class="text-muted" style="grid-column: 1 / -1;">No classes scheduled for today.</p>
              @endforelse
            </div>
          </div>
        </div>

        @if(isset($todayDemos) && $todayDemos->count() > 0)
        <div class="grid grid-12 gap-4 mb-4">
          <div class="card p-3" style="border-left: 4px solid var(--warning);">
            <h4 class="font-semibold text-warning mb-3">Today's Demo Classes</h4>
            <div class="schedule-grid-cols">
              @foreach($todayDemos as $demo)
              <div class="student-class-box" style="border-left-color: var(--warning);">
                  <div class="d-flex justify-between align-center mb-2">
                    <span class="badge badge-primary">{{ $demo->scheduled_at->format('h:i A') }}</span>
                    <span class="text-muted" style="font-size: 0.8rem;">{{ $demo->duration_minutes }} mins</span>
                  </div>
                  <div>
                    <h5 class="font-bold text-main" style="margin-bottom: 0.25rem;">{{ $demo->student_name }}</h5>
                    <p class="text-muted" style="font-size: 0.85rem;">{{ $demo->instrument }} (Demo)</p>
                  </div>
                  <div class="d-flex gap-2 mt-2">
                    <a href="{{ $demo->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="btn btn-primary btn-sm" style="flex:1; text-align:center; text-decoration:none;">Start Demo</a>
                  </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif
      </div>

      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
