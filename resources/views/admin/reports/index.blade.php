@extends('layouts.main')
@section('title', 'Reports')
@section('page', 'reports')

@push('styles')
<style>
.reports-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .reports-stats {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .reports-stats {
        grid-template-columns: 1fr;
      }
    }
</style>
@endpush

@section('content')

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports') }}" class="d-flex gap-3 align-center flex-wrap">
            <div class="form-group" style="margin-bottom:0;">
                <label for="start_date" style="font-size: 0.8rem; font-weight:600;">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? '' }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label for="end_date" style="font-size: 0.8rem; font-weight:600;">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? '' }}">
            </div>
            <div class="d-flex gap-2" style="margin-top: 1.25rem;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <button type="submit" formaction="{{ route('admin.reports.export') }}" class="btn btn-success">⬇️ Export Report (Excel)</button>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary -->
      <div class="reports-stats">
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">📈</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Yearly Enrollment Growth</div>
            <h3 class="font-bold">{{ $enrollmentGrowth >= 0 ? '+' : '' }}{{ $enrollmentGrowth }}%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">🎓</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Active Students Ratio</div>
            <h3 class="font-bold">{{ $activeRatio }}%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">✔️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Class Show Rate</div>
            <h3 class="font-bold">{{ $showRate }}%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">🍂</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Leave Cover Rates</div>
            <h3 class="font-bold">{{ $leaveCoverRate }}%</h3>
          </div>
        </div>
      </div>

      <!-- Charts Matrix -->
      <div class="grid grid-2 gap-4 mb-4">
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold">Student Signups Trend (Monthly)</h4>
          </div>
          <div class="card-body d-flex justify-center align-center">
            <canvas id="signupsLineChart" style="width:100%; height:200px;"></canvas>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold">Class Load Hours by Instrument</h4>
          </div>
          <div class="card-body d-flex justify-center align-center">
            <canvas id="hoursBarChart" style="width:100%; height:200px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Demo vs Conversion Charts Matrix -->
      <div class="grid grid-2 gap-4 mb-4">
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold">Demo Sessions Applied Trend (Monthly)</h4>
          </div>
          <div class="card-body d-flex justify-center align-center">
            <canvas id="demosLineChart" style="width:100%; height:200px;"></canvas>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold">Leads Conversion Performance (Monthly)</h4>
          </div>
          <div class="card-body d-flex justify-center align-center">
            <canvas id="conversionBarChart" style="width:100%; height:200px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Searchable Class History Log (Jquery DataTables) -->
      <div class="card">
        <div class="card-header">
          <h4 class="font-semibold">Class Attendance &amp; History Report</h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="reportsHistoryTable" style="width:100%">
            <thead>
              <tr>
                <th style="width: 50px; text-align: center;">S. No.</th>
                <th style="text-align: center;">Student</th>
                <th style="text-align: center;">Teacher</th>
                <th style="text-align: center;">Instrument</th>
                <th style="text-align: center;">Date &amp; Time</th>
                <th style="text-align: center;">Duration</th>
                <th style="text-align: center;">Status</th>
              </tr>
            </thead>
            <tbody id="reportsTableBody" style="text-align: center;">
              <!-- Loaded dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. Charts Initialization ---
        const labels = @json($labels);
        const signupsData = @json($signupsData);
        const demosData = @json($demosData);
        const conversionData = @json($conversionData);
        const instruments = @json($instruments);
        const hoursData = @json($hoursData);

        // Signups Line Chart
        new Chart(document.getElementById('signupsLineChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Signups',
                    data: signupsData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Class Load Hours Bar Chart
        new Chart(document.getElementById('hoursBarChart'), {
            type: 'bar',
            data: {
                labels: instruments,
                datasets: [{
                    label: 'Hours',
                    data: hoursData,
                    backgroundColor: '#10b981'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Demos Line Chart
        new Chart(document.getElementById('demosLineChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Demos Applied',
                    data: demosData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Conversion Bar Chart
        new Chart(document.getElementById('conversionBarChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Conversions',
                    data: conversionData,
                    backgroundColor: '#8b5cf6'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // --- 2. DataTables Initialization ---
        const historyData = @json($classHistory);
        const tbody = document.getElementById('reportsTableBody');
        
        historyData.forEach((row, index) => {
            const tr = document.createElement('tr');
            
            const startsAt = new Date(row.starts_at);
            const dateStr = startsAt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const timeStr = startsAt.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${row.student_group_id && row.student_group ? '<span style="display:inline-block; padding: 2px 6px; background:#f0fdf4; color:#166534; border-radius:10px; font-size:0.75rem; margin-bottom:4px;">Group Class</span><br>' + row.student_group.name : (row.student && row.student.user ? row.student.user.name : '-')}</td>
                <td>${row.teacher && row.teacher.user ? row.teacher.user.name : '-'}</td>
                <td><span class="badge badge-primary">${row.instrument || '-'}</span></td>
                <td>
                    <div class="font-medium">${dateStr}</div>
                    <div class="text-muted" style="font-size:0.75rem;">${timeStr}</div>
                </td>
                <td>${row.duration_minutes} min</td>
                <td><span class="badge badge-${row.status === 'completed' ? 'success' : (row.status === 'scheduled' ? 'warning' : 'danger')}">${row.status}</span></td>
            `;
            tbody.appendChild(tr);
        });

        $('#reportsHistoryTable').DataTable({
            responsive: true,
            order: [], // Let it keep the DOM order by default
            language: { searchPlaceholder: "Search history..." }
        });
    });
</script>
@endpush
@endsection
