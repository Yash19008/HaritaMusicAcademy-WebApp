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
      border-radius: var(--radius-lg);
      padding: 1.1rem;
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .student-card-banner {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background-color: var(--bg-card);
      background: linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(20, 85, 61, 0.05) 100%);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 1.1rem;
      box-shadow: var(--shadow-sm);
      position: relative;
    }

    .student-card-banner img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2.5px solid var(--primary);
      z-index: 2;
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
      border-radius: var(--radius-lg);
      padding: 1.1rem;
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .student-card-banner {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background-color: var(--bg-card);
      background: linear-gradient(135deg, rgba(13, 148, 136, 0.02) 0%, rgba(20, 85, 61, 0.05) 100%);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 1.1rem;
      box-shadow: var(--shadow-sm);
      position: relative;
    }

    .student-card-banner img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2.5px solid var(--primary);
      z-index: 2;
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
      <!-- STUDENT VIEW CONTAINER -->
      <div id="studentDashboardView" data-role-limit="student" class="slide-up">

        <div class="student-dashboard-layout">
          <!-- Banner card -->
          <div class="student-card-banner">
            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&amp;fit=crop&amp;q=80&amp;w=200&amp;h=200" alt="{{ $student->name ?? 'Student' }} Profile">
            <div>
              <span class="text-muted font-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Student Portal</span>
              <h2 class="text-serif text-primary" style="font-size: 1.4rem; margin-bottom: 0.25rem;">Welcome back, {{ explode(' ', $student->name ?? 'Student')[0] }}! 👋</h2>
              <p class="text-muted mb-2" style="font-size: 0.85rem;">Keep practicing, great music accomplishments take time.</p>
              @if($nextClass)
                  <button class="btn btn-primary btn-sm" onclick="alert('Launching Live Audio/Video Stream room...')">Join Next Class</button>
              @else
                  <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('student.my-classes') }}'">View Schedule</button>
              @endif
            </div>
          </div>

          <!-- Next Class Box -->
          <div class="student-card">
            @if($nextClass)
                <div>
                  <span class="form-label" style="font-size: 0.7rem;">Next Class</span>
                  <h4 class="font-semibold text-primary mt-1">{{ $nextClass->title ?? ($student->instrument ?? 'Music Class') }}</h4>
                  <p class="text-muted" style="font-size: 0.8rem;">with <a href="javascript:void(0)" class="text-primary hover-underline font-semibold">{{ $nextClass->teacher->user->name ?? 'Instructor' }}</a></p>
                </div>
                <div class="student-class-box mt-2">
                  <div class="font-semibold" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($nextClass->starts_at)->format('l, h:i A') }}</div>
                  <div class="text-muted" style="font-size: 0.7rem;">Duration: {{ \Carbon\Carbon::parse($nextClass->ends_at)->diffInMinutes($nextClass->starts_at) }} mins</div>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="window.location.href='{{ route('student.my-classes') }}'">Reschedule</button>
            @else
                <div>
                  <span class="form-label" style="font-size: 0.7rem;">Next Class</span>
                  <h4 class="font-semibold text-primary mt-1">No Upcoming Classes</h4>
                  <p class="text-muted" style="font-size: 0.8rem;">You have no classes scheduled.</p>
                </div>
            @endif
          </div>

          <!-- Progress Ring Card -->
          <div class="student-card align-center text-center">
            <span class="form-label">Progress Overview</span>
            <div class="text-muted mb-2" style="font-size: 0.75rem;">Keep going, you're doing amazing!</div>

            <div id="studentProgressRing" class="progress-ring-container my-2"></div>

            <div class="w-100 mt-2" style="font-size: 0.8rem; text-align: left;">
              <div class="d-flex justify-between border-bottom p-1">
                <span>Classes Completed</span>
                <span class="font-bold">{{ $completedClassesCount }} / {{ $totalClassesCount }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Transaction Log -->
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold">Credit Transaction Log</h4>
          </div>
          <div class="card-body p-3">
            <table class="table display responsive nowrap" id="transactionsTable" style="width:100%">
              <thead>
                <tr>
                  <th>Timestamp</th>
                  <th>Student Name</th>
                  <th>Action</th>
                  <th>Quantity</th>
                  <th>Reason / Remarks</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                        <td class="font-semibold">{{ $student->name ?? 'Student' }}</td>
                        <td class="font-bold {{ in_array(strtolower($txn->action), ['added', 'add']) ? 'text-success' : 'text-danger' }}">
                            {{ in_array(strtolower($txn->action), ['added', 'add']) ? '+' : '-' }}{{ abs($txn->quantity) }} Credits
                        </td>
                        <td>{{ abs($txn->quantity) }}</td>
                        <td>{{ $txn->reason }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No transactions found.</td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#transactionsTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 5,
        "language": {
            "search": "",
            "searchPlaceholder": "Search logs..."
        }
    });

    // Draw simple progress ring
    const percentage = {{ $totalClassesCount > 0 ? round(($completedClassesCount / $totalClassesCount) * 100) : 0 }};
    const container = document.getElementById('studentProgressRing');
    if (container) {
        container.innerHTML = `
            <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e2e8f0" stroke-width="10" />
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="10" 
                            stroke-dasharray="314.159" stroke-dashoffset="${314.159 - (314.159 * percentage) / 100}" 
                            stroke-linecap="round" transform="rotate(-90 60 60)" />
                </svg>
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <span style="font-size: 1.5rem; font-weight: bold; color: #10b981;">${percentage}%</span>
                </div>
            </div>
        `;
    }
});
</script>
@endpush
