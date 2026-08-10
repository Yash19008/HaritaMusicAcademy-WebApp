@extends('layouts.main')
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
<!-- Stats Summary -->
      <div class="reports-stats">
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">📈</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Yearly Enrollment Growth</div>
            <h3 class="font-bold">+45.2%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">🎓</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Active Students Ratio</div>
            <h3 class="font-bold">92.4%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">✔️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Class Show Rate</div>
            <h3 class="font-bold">98.1%</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-2">
          <div class="stat-icon">🍂</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem;">Leave Cover Rates</div>
            <h3 class="font-bold">100%</h3>
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
                <th>Class ID</th>
                <th>Student</th>
                <th>Teacher</th>
                <th>Instrument</th>
                <th>Date &amp; Time</th>
                <th>Duration</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="reportsTableBody">
              <!-- Loaded dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
