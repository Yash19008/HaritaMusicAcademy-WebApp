@extends('layouts.main')
@section('title', 'Dashboard')
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

      <div id="adminDashboardView" data-role-limit="admin" class="slide-up">
        <!-- 4 Stat boxes -->
        <div class="stat-card-grid">
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">🎓</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Students</div>
              <h3 id="adminTotalStudents" class="font-bold">{{ $totalStudents }}</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">🎻</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Teachers</div>
              <h3 id="adminTotalTeachers" class="font-bold">{{ $totalTeachers }}</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">📅</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Today's Classes</div>
              <h3 id="adminTodayClasses" class="font-bold">{{ $todayClasses }}</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">💰</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Monthly Sales</div>
              <h3 id="adminMonthlySales" class="font-bold">₹{{ number_format($monthlySales) }}</h3>
            </div>
          </div>
        </div>

        <!-- Quick Admin Actions Card -->
        <div class="card mb-4">
          <div class="card-header">
            <h4 class="font-semibold">⚡ Quick Actions Panel</h4>
          </div>
          <div class="card-body d-flex gap-3 flex-wrap">
            <a href="{{ route('admin.students') }}" class="btn btn-secondary btn-sm">➕ Add Student</a>
            <a href="{{ route('admin.teachers') }}" class="btn btn-secondary btn-sm">➕ Add Teacher</a>
            <a href="{{ route('admin.credits') }}" class="btn btn-secondary btn-sm">🪙 Adjust Credits</a>
            <a href="{{ route('admin.demos') }}" class="btn btn-secondary btn-sm">🎧 Demo Classes</a>
            <a href="{{ route('admin.class-booking') }}" class="btn btn-primary btn-sm">📅 Schedule Class</a>
          </div>
        </div>

        <!-- Charts grid -->
        <div class="grid grid-2 gap-4 mb-4">
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">Revenue Trend (INR)</h4>
            </div>
            <div class="card-body d-flex justify-center align-center">
              <canvas id="revenueLineChart" style="width: 100%; height: 200px;"></canvas>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">Classes Booked by Instrument</h4>
            </div>
            <div class="card-body d-flex justify-center align-center">
              <canvas id="instrumentBarChart" style="width: 100%; height: 200px;"></canvas>
            </div>
          </div>
        </div>

        <!-- Additional Charts grid -->
        <div class="grid grid-2 gap-4 mb-4">
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">Total Students Enrolled</h4>
            </div>
            <div class="card-body d-flex justify-center align-center">
              <canvas id="studentsEnrolledChart" style="width: 100%; height: 200px;"></canvas>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">Total Teachers Onboarded</h4>
            </div>
            <div class="card-body d-flex justify-center align-center">
              <canvas id="teachersOnboardedChart" style="width: 100%; height: 200px;"></canvas>
            </div>
          </div>
        </div>

        <!-- Lower Information Grid -->
        <div class="grid grid-3 gap-4 mb-4">
          <!-- Hot Leads -->
          <div class="card" style="border: 1px solid #f59e0b;">
            <div class="card-header" style="background-color: #fef3c7;">
              <h4 class="font-semibold" style="color: #d97706;">🔥 Hot Leads (Low Credits)</h4>
            </div>
            <div class="card-body p-0">
              @if($renewalInterests->isEmpty())
                <div class="p-3 text-center text-muted" style="font-size: 0.85rem;">No active leads right now.</div>
              @else
                @foreach($renewalInterests as $lead)
                  <div class="d-flex align-center justify-between p-2 border-bottom" style="font-size: 13.5px;">
                    <div>
                      <strong>{{ $lead->user->name ?? $lead->name }}</strong>
                      <div class="text-light" style="font-size: 11px;">Interested in renewal</div>
                    </div>
                    <a href="{{ route('admin.students') }}?search={{ urlencode($lead->email) }}" class="btn btn-sm btn-primary" style="padding: 2px 8px; font-size: 11px;">View</a>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">🔔 Recent Academy Activity</h4>
            </div>
            <div class="card-body p-0">
              @if($recentActivity->isEmpty())
                <div class="p-3 text-center text-muted" style="font-size: 0.85rem;">No recent activity.</div>
              @else
                @foreach($recentActivity as $activity)
                <div class="d-flex align-center gap-3 p-2 border-bottom" style="font-size: 13px;">
                  <span>📅</span>
                  <div>
                    <strong>Class Booked</strong>: {{ $activity->student->name ?? 'Unknown Student' }} with {{ $activity->teacher->name ?? 'Unknown Teacher' }} ({{ $activity->instrument }})
                    <div class="text-light" style="font-size: 11px; margin-top: 2px;">{{ $activity->created_at->diffForHumans() }}</div>
                  </div>
                </div>
                @endforeach
              @endif
            </div>
          </div>

          <!-- Top Instructors -->
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">⭐ Top Rated Instructors</h4>
            </div>
            <div class="card-body p-0">
              @if($topTeachers->isEmpty())
                <div class="p-3 text-center text-muted" style="font-size: 0.85rem;">No teachers yet.</div>
              @else
                @foreach($topTeachers as $teacher)
                <div class="d-flex align-center justify-between p-2 border-bottom" style="font-size: 13.5px;">
                  <div class="d-flex align-center">
                    <span class="table-avatar" style="background-color: var(--secondary-light);">{{ substr($teacher->name, 0, 2) }}</span>
                    <div>
                      <strong>{{ $teacher->name }}</strong>
                      <div class="text-light" style="font-size: 11px;">{{ $teacher->categories ?? 'General' }}</div>
                    </div>
                  </div>
                  <span style="color: #eab308; font-weight: 700;">{{ $teacher->rating ? number_format($teacher->rating, 1) : '5.0' }} ⭐ ({{ $teacher->class_bookings_count ?? 0 }} classes)</span>
                </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>

      @endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('admin-assets/js/charts.js') }}"></script>
<script>

    // Draw Charts when on Dashboard
    document.addEventListener("DOMContentLoaded", () => {
      renderDashboardCharts();
    });

    function renderDashboardCharts() {
      ChartManager.drawLineChart("revenueLineChart", @json($revenueData), @json($chartLabels));
      ChartManager.drawBarChart("instrumentBarChart", @json(array_values($instrumentCount)), @json($instrumentData));
      ChartManager.drawLineChart("studentsEnrolledChart", @json($studentsData), @json($chartLabels));
      ChartManager.drawBarChart("teachersOnboardedChart", @json($teachersData), @json($chartLabels));
    }
  
</script>
@endpush
