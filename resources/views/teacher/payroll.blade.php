@extends('layouts.main')
@section('title', 'Payroll')
@section('page', 'payroll')

@push('styles')
<!-- jQuery & DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
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

    .payroll-grid {
      display: grid;
      grid-template-columns: 1.2fr 1.8fr;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .payroll-grid {
        grid-template-columns: 1fr;
      }
    }

    .salary-calc-box {
      border: 1.5px solid var(--primary);
      border-radius: var(--radius-md);
      padding: 1.25rem;
      margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<!-- Stats Grid -->
<div class="payroll-stats slide-up">
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">💳</div>
        <div>
        <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Per Class Rate</div>
        <h3 class="font-bold">₹{{ $currentPayroll ? number_format($currentPayroll->per_class_rate) : 500 }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">📊</div>
        <div>
        <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Classes Taught</div>
        <h3 class="font-bold">{{ $currentPayroll ? $currentPayroll->classes_taken : 0 }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--warning-bg); color: var(--warning)">⭐</div>
        <div>
        <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Opportunities Taken</div>
        <h3 class="font-bold">{{ $currentPayroll ? $currentPayroll->opportunity_taken : 0 }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">💰</div>
        <div>
        <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Estimated Payout</div>
        <h3 class="font-bold">₹{{ $currentPayroll ? number_format($currentPayroll->calculated_salary) : 0 }}</h3>
        </div>
    </div>
</div>

<!-- Main Payroll Display Grid -->
<div class="payroll-grid slide-up">

    <!-- Payout Calculation Details -->
    <div class="card">
        <div class="card-header">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Salary Slip - {{ $currentMonthName }}</h4>
        </div>
        <div class="card-body p-4">
        @if($currentPayroll)
        <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
            <span class="text-muted">Teacher Name</span>
            <span class="font-bold">{{ auth()->user()->name }}</span>
        </div>
        <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
            <span class="text-muted">Status</span>
            <span class="badge {{ $currentPayroll->status == 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($currentPayroll->status) }}</span>
        </div>
        <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
            <span class="text-muted">Base Salary (Dynamic)</span>
            <span class="font-semibold">₹{{ number_format($currentPayroll->per_class_rate * $currentPayroll->classes_taken) }} <small class="text-muted">(Rate × {{ $currentPayroll->classes_taken }} Classes)</small></span>
        </div>
        <div class="info-list-item" style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid var(--border-light); font-size:0.85rem;">
            <span class="text-muted">Opportunities Bonus</span>
            <span class="font-semibold">₹{{ number_format(0.20 * $currentPayroll->per_class_rate * $currentPayroll->opportunity_taken) }} <small class="text-muted">(20% Rate × {{ $currentPayroll->opportunity_taken }} Demos)</small></span>
        </div>

        <!-- Calculation Box -->
        <div class="salary-calc-box">
            <div class="font-bold text-primary mb-2" style="font-size: 0.85rem; text-transform: uppercase;">Academy Ledger Formula</div>

            <div class="mb-2" style="font-size:0.8rem; line-height: 1.45;">
            <span class="font-medium">Standard Pack Salary (Estimate):</span><br>
            <code>(Rate * 10) + (20% of Rate * 5)</code><br>
            = <code>({{ $currentPayroll->per_class_rate }} * 10) + ({{ 0.20 * $currentPayroll->per_class_rate }} * 5)</code> = <b class="text-serif font-bold">₹{{ number_format($currentPayroll->formula_salary) }}</b>
            </div>

            <div style="font-size:0.8rem; line-height: 1.45; border-top: 1px solid var(--primary); padding-top: 0.5rem; margin-top: 0.5rem;">
            <span class="text-muted font-medium">This Month Actual Payout:</span><br>
            <code>(Rate * {{ $currentPayroll->classes_taken }}) + (20% of Rate * {{ $currentPayroll->opportunity_taken }})</code><br>
            = <code>({{ $currentPayroll->per_class_rate }} * {{ $currentPayroll->classes_taken }}) + ({{ 0.20 * $currentPayroll->per_class_rate }} * {{ $currentPayroll->opportunity_taken }})</code> = <b class="text-serif font-bold" style="font-size:1.1rem; color: var(--secondary);">₹{{ number_format($currentPayroll->calculated_salary) }}</b>
            </div>
        </div>
        @else
        <div class="p-4 text-center text-muted" style="background: var(--bg-body); border: 1px dashed var(--border-color); border-radius: var(--radius-md);">
            <p style="font-size: 0.85rem; margin: 0;">Payroll for this month hasn't been generated yet.</p>
        </div>
        @endif
        </div>
    </div>

    <!-- Days Taught Detail Logs -->
    <div class="card">
        <div class="card-header">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Days Taught Ledger ({{ $currentMonthName }})</h4>
        </div>
        <div class="card-body p-3">
        <table class="table display responsive nowrap" id="payrollTable" style="width:100%">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Class / Student Details</th>
            </tr>
            </thead>
            <tbody>
            @foreach($recentClasses as $class)
            <tr>
                <td class="font-semibold">{{ \Carbon\Carbon::parse($class->starts_at)->format('d M, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($class->starts_at)->format('h:i A') }}</td>
                <td>
                    {{ $class->instrument }} <br>
                    <small class="text-muted">Student: {{ $class->student->user->name ?? 'Unknown' }}</small>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

</div>

<!-- Historical Payroll Display -->
@if($payrolls->count() > 0)
<div class="card slide-up" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Historical Salary Ledger</h4>
    </div>
    <div class="card-body p-3">
        <table class="table display responsive nowrap" id="historicalPayrollTable" style="width:100%">
            <thead>
                <tr>
                    <th>Month & Year</th>
                    <th>Rate</th>
                    <th>Classes</th>
                    <th>Opportunities</th>
                    <th>Total Payout</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrolls as $p)
                <tr>
                    <td class="font-bold">{{ $p->month }}</td>
                    <td>₹{{ number_format($p->per_class_rate) }}</td>
                    <td>{{ $p->classes_taken }}</td>
                    <td>{{ $p->opportunity_taken }}</td>
                    <td class="font-bold text-primary">₹{{ number_format($p->calculated_salary) }}</td>
                    <td><span class="badge {{ $p->status == 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    $('#payrollTable').DataTable({
        responsive: true,
        pageLength: 5,
        lengthChange: false
    });
    
    if ($('#historicalPayrollTable').length) {
        $('#historicalPayrollTable').DataTable({
            responsive: true,
            pageLength: 5,
            lengthChange: false
        });
    }
});
</script>
@endpush
