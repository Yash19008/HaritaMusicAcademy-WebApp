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
</style>
@endpush

@section('content')
<!-- Header actions -->
<div class="d-flex justify-between align-center mb-4">
    <h2 class="font-semibold" style="font-size: 1.5rem;">Payroll Administration</h2>
    <div class="d-flex gap-2">
        @if(isset($payrolls) && $payrolls->where('status', 'pending')->count() > 0)
        <form action="{{ route('admin.payroll.disburse-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" style="background: var(--success); border-color: var(--success);">Disburse All Pending</button>
        </form>
        @endif
    </div>
</div>

<!-- Stats Summary -->
<div class="payroll-stats">
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--warning-bg); color: var(--warning)">📅</div>
        <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Current Month</div>
            <h3 class="font-bold">{{ $currentMonth }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">💰</div>
        <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Payout</div>
            <h3 class="font-bold">₹{{ number_format($totalPayout) }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">👨‍🏫</div>
        <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Instructors</div>
            <h3 class="font-bold">{{ $totalTeachers }}</h3>
        </div>
    </div>
    <div class="card p-3 d-flex align-center gap-3">
        <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">✔️</div>
        <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Opportunities</div>
            <h3 class="font-bold text-warning" style="font-size:1.15rem;">{{ $totalOpportunity }}</h3>
        </div>
    </div>
</div>

<!-- Master Payroll Table -->
<div class="card">
    <div class="card-header d-flex align-center justify-between">
        <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Staff Payroll Ledger</h4>
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
            <th>Formula Salary</th>
            <th>Actual Salary</th>
            <th>Status</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($payrolls))
            @foreach($payrolls as $payroll)
            <tr>
                <td class="font-bold">{{ $payroll->teacher->user->name }}</td>
                <td>{{ $payroll->month }}</td>
                <td>
                    <div class="d-flex align-center gap-1">
                        <span>₹{{ number_format($payroll->per_class_rate) }}</span>
                        <button type="button" class="btn btn-secondary btn-sm p-1" style="min-width:auto; padding:2px 6px !important; font-size:10px; border-radius:4px;" onclick="editClassRate({{ $payroll->id }}, {{ $payroll->per_class_rate }}, '{{ $payroll->teacher->user->name }}')">✏️</button>
                    </div>
                </td>
                <td class="font-semibold">{{ $payroll->classes_taken }} classes</td>
                <td>{{ $payroll->opportunity_taken }}</td>
                <td class="text-muted">₹{{ number_format($payroll->formula_salary) }}</td>
                <td class="font-bold text-primary">₹{{ number_format($payroll->calculated_salary) }}</td>
                <td>
                    <span class="badge {{ $payroll->status == 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($payroll->status) }}</span>
                </td>
                <td>
                    @if($payroll->status !== 'paid')
                    <form action="{{ route('admin.payroll.disburse', $payroll->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-height: unset; background: var(--success); border-color: var(--success); color: white;">Disburse</button>
                    </form>
                    @else
                    <span class="text-muted" style="font-size: 0.8rem;">Paid</span>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
        </table>
    </div>
</div>

<!-- Edit Rate Form (Hidden) -->
<form id="editRateForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="number" name="per_class_rate" id="per_class_rate_input">
</form>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#adminPayrollTable')) {
        $('#adminPayrollTable').DataTable().destroy();
    }
    $('#adminPayrollTable').DataTable({
        responsive: true
    });
});

function editClassRate(id, currentRate, name) {
    const newRate = prompt(`Modify Per Class Rate for ${name}:`, currentRate);
    if (newRate !== null) {
        const rateVal = parseInt(newRate);
        if (isNaN(rateVal) || rateVal <= 0) {
            alert("Please enter a valid numeric rate!");
            return;
        }
        
        const form = document.getElementById('editRateForm');
        form.action = `/admin/payroll/${id}/rate`;
        document.getElementById('per_class_rate_input').value = rateVal;
        form.submit();
    }
}
</script>
@endpush
