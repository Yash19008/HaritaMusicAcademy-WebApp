@extends('layouts.main')
@section('page', 'demos')

@push('styles')
<style>
.stat-card-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
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
      width: 48px;
      height: 48px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .badge-select {
      font-size: 11.5px;
      font-weight: 600;
      padding: 0.25rem 0.5rem;
      border-radius: var(--radius-sm);
      outline: none;
      transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<!-- KPI Stats -->
      <div class="stat-card-grid">
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #eff6ff; color: #1e40af">📅</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Scheduled Demos</div>
            <h3 id="statScheduled" class="font-bold">0</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">✔️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Completed Demos</div>
            <h3 id="statCompleted" class="font-bold">0</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #fef3c7; color: #b45309;">👤</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Converted Students</div>
            <h3 id="statConverted" class="font-bold">0</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #fee2e2; color: #b91c1c;">❌</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Cancelled / No Show</div>
            <h3 id="statCancelled" class="font-bold">0</h3>
          </div>
        </div>
      </div>

      <!-- Demo Classes Log -->
      <div class="card">
        <div class="card-header d-flex align-center justify-between">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Demo Session Ledger</h4>
          <input type="text" id="demoSearch" class="form-control" placeholder="Quick search..." style="width: 180px;">
        </div>
        <div class="card-body p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
          <table class="table display responsive nowrap" id="demosTable" style="width:100%">
            <thead>
              <tr>
                <th data-priority="7">Demo ID</th>
                <th data-priority="1">Student Name</th>
                <th data-priority="3">Instrument</th>
                <th data-priority="4">Assigned Teacher</th>
                <th data-priority="5">Scheduled Date &amp; Time</th>
                <th data-priority="6">Duration</th>
                <th data-priority="1">Status (Update Inline)</th>
              </tr>
            </thead>
            <tbody id="demosTableBody">
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
@endsection
