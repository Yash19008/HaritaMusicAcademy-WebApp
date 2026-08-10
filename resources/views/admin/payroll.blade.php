@extends('layouts.main')
@section('page', 'payroll')

@push('styles')
<style>
.payroll-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .payroll-stats {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .payroll-stats {
        grid-template-columns: 1fr;
      }
    }
</style>
@endpush

@section('content')
<!-- Stats Summary -->
      <div class="payroll-stats">
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--warning-bg); color: var(--warning)">📅</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Current Month</div>
            <h3 class="font-bold">July 2026</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">💰</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Payout Ledger</div>
            <h3 id="adminTotalPayoutText" class="font-bold">₹12,500</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">👨‍🏫</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Instructors</div>
            <h3 class="font-bold">1</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">✔️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Opportunity Taken</div>
            <h3 id="disburseStatusText" class="font-bold text-warning" style="font-size:1.15rem;">10</h3>
          </div>
        </div>
      </div>

      <!-- Master Payroll Table -->
      <div class="card">
        <div class="card-header d-flex align-center justify-between">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Staff Payroll Ledger
          </h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="adminPayrollTable" style="width:100%">
            <thead>
              <tr>
                <th>Teacher Name</th>
                <th>Month &amp; Year</th>
                <th>Per Class Rate (INR)</th>
                <th>Classes Taken</th>
                <th>Opportunity Taken</th>
                <th>Formula Salary (Static)</th>
                <th>Calculated Salary (Actual)</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="adminPayrollBody">
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
@endsection
