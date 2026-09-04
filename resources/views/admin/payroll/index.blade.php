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
                    <th>Teacher</th>
                    <th>Month</th>
                    <th>Class Rate</th>
                    <th>Classes</th>
                    <th>Demos</th>
                    <th>Emergency</th>
                    <th>Referrals</th>
                    <th>Est. Standard Payout</th>
                    <th>Actual Calculated</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($payrolls))
            @foreach($payrolls as $payroll)
            <tr>
                <td class="font-bold">{{ $payroll->teacher->user->name }}</td>
                <td>{{ $payroll->month }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span>₹{{ number_format($payroll->per_class_rate) }}</span>
                        <button type="button" class="btn btn-secondary btn-sm p-1" style="min-width:auto; padding:2px 6px !important; font-size:10px; border-radius:4px;" onclick="editClassRate({{ $payroll->id }}, {{ $payroll->per_class_rate }}, '{{ $payroll->teacher->user->name }}')">✏️</button>
                    </div>
                </td>
                <td class="font-semibold">{{ $payroll->classes_taken }} classes</td>
                <td>{{ $payroll->demo_classes }}</td>
                <td>{{ $payroll->emergency_classes }}</td>
                <td>{{ $payroll->referrals }}</td>
                <td class="text-muted">₹{{ number_format($payroll->formula_salary) }}</td>
                <td class="font-bold text-primary">₹{{ number_format($payroll->calculated_salary) }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
        </table>
    </div>
</div>

@endsection

@push('modals')
<!-- Edit Rate Modal -->
<div id="editRateModal" class="modal-backdrop">
  <div class="modal" style="max-width: 400px;">
    <div class="modal-header">
      <h3 class="font-semibold text-serif">Edit Per Class Rate</h3>
      <button class="modal-close" onclick="hideModal('editRateModal')">×</button>
    </div>
    <form id="editRateModalForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <p class="mb-3 text-muted" id="editRateTeacherName"></p>
        <div class="form-group mb-3">
          <label class="form-label" for="modal_per_class_rate">New Rate (INR)</label>
          <input type="number" id="modal_per_class_rate" name="per_class_rate" class="form-control" required min="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideModal('editRateModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
@endpush

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
    const form = document.getElementById('editRateModalForm');
    form.action = `/admin/payroll/${id}/rate`;
    
    document.getElementById('editRateTeacherName').innerText = `Modify Per Class Rate for ${name}:`;
    document.getElementById('modal_per_class_rate').value = currentRate;
    
    if (typeof showModal === 'function') {
        showModal('editRateModal');
    } else {
        document.getElementById('editRateModal').classList.add('show');
    }
}
</script>
@endpush
