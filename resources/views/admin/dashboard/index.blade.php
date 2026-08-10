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
              <h3 id="adminTotalStudents" class="font-bold">5</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">🎻</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Teachers</div>
              <h3 id="adminTotalTeachers" class="font-bold">4</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">📅</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Today's Classes</div>
              <h3 id="adminTodayClasses" class="font-bold">1</h3>
            </div>
          </div>
          <div class="card p-3 d-flex align-center gap-3 stat-card">
            <div class="stat-icon">💰</div>
            <div>
              <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Monthly Sales</div>
              <h3 id="adminMonthlySales" class="font-bold">₹41,000</h3>
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
        <div class="grid grid-2 gap-4 mb-4">
          <!-- Recent Activity -->
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">🔔 Recent Academy Activity</h4>
            </div>
            <div class="card-body p-0">
              <div class="d-flex align-center gap-3 p-2 border-bottom" style="font-size: 13px;">
                <span>🟢</span>
                <div>
                  <strong>New Student Registered</strong>: Anirudh Ravichander enrolled in Flute class.
                  <div class="text-light" style="font-size: 11px; margin-top: 2px;">5 minutes ago</div>
                </div>
              </div>
              <div class="d-flex align-center gap-3 p-2 border-bottom" style="font-size: 13px;">
                <span>💳</span>
                <div>
                  <strong>Payment Confirmed</strong>: Aria Sharma purchased Tabla 20-Class Package.
                  <div class="text-light" style="font-size: 11px; margin-top: 2px;">2 hours ago</div>
                </div>
              </div>
              <div class="d-flex align-center gap-3 p-2" style="font-size: 13px;">
                <span>📅</span>
                <div>
                  <strong>Class Rescheduled</strong>: Sitar Intermediate (Rohan Malhotra) moved to tomorrow.
                  <div class="text-light" style="font-size: 11px; margin-top: 2px;">Yesterday</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Top Instructors -->
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">⭐ Top Rated Instructors</h4>
            </div>
            <div class="card-body p-0">
              <div class="d-flex align-center justify-between p-2 border-bottom" style="font-size: 13.5px;">
                <div class="d-flex align-center">
                  <span class="table-avatar" style="background-color: var(--secondary-light);">MS</span>
                  <div>
                    <strong>Meera Sharma</strong>
                    <div class="text-light" style="font-size: 11px;">Vocal & Violin Specialist</div>
                  </div>
                </div>
                <span style="color: #eab308; font-weight: 700;">5.0 ⭐ (24 classes)</span>
              </div>
              <div class="d-flex align-center justify-between p-2 border-bottom" style="font-size: 13.5px;">
                <div class="d-flex align-center">
                  <span class="table-avatar" style="background-color: var(--secondary-light);">RS</span>
                  <div>
                    <strong>Pandit Ravi Sen</strong>
                    <div class="text-light" style="font-size: 11px;">Sitar Maestro</div>
                  </div>
                </div>
                <span style="color: #eab308; font-weight: 700;">4.9 ⭐ (18 classes)</span>
              </div>
              <div class="d-flex align-center justify-between p-2" style="font-size: 13.5px;">
                <div class="d-flex align-center">
                  <span class="table-avatar" style="background-color: var(--secondary-light);">HP</span>
                  <div>
                    <strong>Hari Prasad Jr</strong>
                    <div class="text-light" style="font-size: 11px;">Flute Specialist</div>
                  </div>
                </div>
                <span style="color: #eab308; font-weight: 700;">4.8 ⭐ (15 classes)</span>
              </div>
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
      populateDynamicMetrics();
    });

    function onRoleChange() {
      renderDashboardCharts();
      populateDynamicMetrics();
    }

    function populateDynamicMetrics() {
      const role = db.getCurrentRole();

      if (role === 'admin') {
        const students = db.getStudents();
        const teachers = db.getTeachers();
        const classes = db.getClasses();
        const sales = db.getSales();

        const totalSales = sales.reduce((acc, curr) => acc + curr.amount, 0);

        document.getElementById('adminTotalStudents').textContent = students.length;
        document.getElementById('adminTotalTeachers').textContent = teachers.length;
        document.getElementById('adminTodayClasses').textContent = classes.filter(c => c.status === 'Scheduled').length;
        document.getElementById('adminMonthlySales').textContent = "₹" + totalSales.toLocaleString('en-IN');
      }

      else if (role === 'teacher') {
        const classes = db.getClasses();
        const scheduleContainer = document.getElementById('teacherTodaySchedule');
        scheduleContainer.innerHTML = "";

        const meeraClasses = classes.filter(c => c.teacherName === "Meera Sharma" && c.status !== "Completed");

        if (meeraClasses.length === 0) {
          scheduleContainer.innerHTML = "<div class='text-muted p-2'>No scheduled classes for today.</div>";
        } else {
          meeraClasses.forEach(cls => {
            const dateObj = new Date(cls.dateTime);
            const timeStr = dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

            const div = document.createElement('div');
            div.className = "student-class-box";
            div.innerHTML = `
              <div class="font-semibold">${cls.studentName} - ${cls.instrument}</div>
              <div class="text-muted" style="font-size: 0.75rem;">Time: ${timeStr}</div>
              <div class="d-flex gap-2 mt-2">
                <button class="btn btn-primary btn-sm p-1 px-2" style="font-size: 0.7rem;" onclick="alert('Starting class session...')">Start Class</button>
                <button class="btn btn-secondary btn-sm p-1 px-2" style="font-size: 0.7rem;" onclick="window.location.href='class-booking.html'">Reschedule</button>
              </div>
            `;
            scheduleContainer.appendChild(div);
          });
        }

        ChartManager.drawProgressRing("teacherAvailabilityProgress", 90, "#059669");
        renderCalendarWidget();
      }

      else if (role === 'student') {
        ChartManager.drawProgressRing("studentProgressRing", 75, "#10b981");
        renderCalendarWidget();
      }
    }

    function renderDashboardCharts() {
      const role = db.getCurrentRole();
      if (role === 'admin') {
        ChartManager.drawLineChart("revenueLineChart", [22000, 29000, 31000, 25000, 41000], ["Mar", "Apr", "May", "Jun", "Jul"]);
        ChartManager.drawBarChart("instrumentBarChart", [15, 8, 12, 5, 9], ["Vocal", "Sitar", "Violin", "Flute", "Tabla"]);
        ChartManager.drawLineChart("studentsEnrolledChart", [12, 18, 25, 38, 50], ["Mar", "Apr", "May", "Jun", "Jul"]);
        ChartManager.drawBarChart("teachersOnboardedChart", [4, 8, 12, 18, 25], ["Mar", "Apr", "May", "Jun", "Jul"]);
      }
    }

    function renderCalendarWidget() {
      const container = document.getElementById("dashboardCalendar");
      if (!container) return;

      container.innerHTML = "";

      const startDay = 3;
      const totalDays = 31;

      const daysHeaders = ["S", "M", "T", "W", "T", "F", "S"];
      daysHeaders.forEach(day => {
        const div = document.createElement("div");
        div.className = "calendar-day-header";
        div.textContent = day;
        container.appendChild(div);
      });

      for (let i = 0; i < startDay; i++) {
        const div = document.createElement("div");
        div.className = "calendar-day-cell text-muted";
        div.innerHTML = "";
        container.appendChild(div);
      }

      for (let day = 1; day <= totalDays; day++) {
        const div = document.createElement("div");
        div.className = "calendar-day-cell";
        div.textContent = day;

        if (day === 24) {
          div.classList.add("today");
        }

        if (day === 24 || day === 26) {
          div.classList.add("active-class");
        }

        container.appendChild(div);
      }
    }
  
</script>
@endpush
