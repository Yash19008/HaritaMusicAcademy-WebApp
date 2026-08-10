@extends('layouts.main')
@section('title', 'Credit Management')
@section('page', 'credits')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .table-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--primary); color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; flex-shrink: 0; margin-right: .5rem;
  }
</style>
@endpush

@section('content')

@if(session('success'))
  <div style="padding:.75rem 1rem;margin-bottom:1rem;background:#ecfdf5;color:#059669;border-radius:var(--radius-sm);font-size:13.5px;">✅ {{ session('success') }}</div>
@endif
@if($errors->any())
  <div style="padding:.75rem 1rem;margin-bottom:1rem;background:#fef2f2;color:#dc2626;border-radius:var(--radius-sm);font-size:13.5px;">
    ❌ @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
  </div>
@endif

{{-- Student Balances Table --}}
<div class="card mb-4">
  <div class="card-header d-flex justify-between align-center">
    <h4 class="font-semibold text-serif">Student Credit Balances</h4>
    <input type="text" id="creditSearch" class="form-control" placeholder="Quick filter name..." style="width:200px;" oninput="if(dtBalances) dtBalances.search(this.value).draw()">
  </div>
  <div class="card-body p-3" style="overflow-x:auto;">
    <table class="table display responsive nowrap" id="balancesTable" style="width:100%">
      <thead>
        <tr>
          <th data-priority="1">Student</th>
          <th data-priority="3">Course</th>
          <th data-priority="2" class="text-center">Current Balance</th>
          <th data-priority="1" style="text-align:center;">Adjust Credits</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $s)
        <tr>
          <td>
            <div class="d-flex align-center">
              <span class="table-avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</span>
              <span class="font-semibold">{{ $s->name }}</span>
            </div>
          </td>
          <td><span class="badge badge-primary">{{ $s->course->name ?? 'N/A' }}</span></td>
          <td class="text-center font-bold">
            @if($s->credits === 0)
              <span class="badge badge-danger mr-1">EMPTY</span>
            @elseif($s->credits < 5)
              <span class="badge badge-warning mr-1">LOW</span>
            @endif
            {{ $s->credits }} Credits
          </td>
          <td style="text-align:center;">
            <div class="actions-dropdown-container" style="display:inline-block;">
              <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event, this)">⋮</button>
              <div class="actions-dropdown-menu" style="min-width:160px;">
                <button class="actions-dropdown-item" onclick="openQuickAdjust({{ $s->id }}, 'add')">🪙 Add Credits</button>
                <button class="actions-dropdown-item text-danger" onclick="openQuickAdjust({{ $s->id }}, 'deduct')">💸 Deduct Credits</button>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center py-4 text-muted">No students found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Transaction Log --}}
<div class="card">
  <div class="card-header">
    <h4 class="font-semibold text-serif">Credit Transaction Log</h4>
  </div>
  <div class="card-body p-3" style="overflow-x:auto;">
    <table class="table display responsive nowrap" id="transactionsTable" style="width:100%">
      <thead>
        <tr>
          <th data-priority="2">Date & Time</th>
          <th data-priority="1">Student Name</th>
          <th data-priority="3">Action</th>
          <th data-priority="4">Quantity</th>
          <th data-priority="5">Reason / Remarks</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $log)
        <tr>
          <td style="font-size:12.5px;">{{ $log->created_at->format('M d, Y h:i A') }}</td>
          <td class="font-semibold">{{ $log->student->name ?? 'Deleted Student' }}</td>
          <td class="font-bold {{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
            {{ $log->action }}
          </td>
          <td class="font-bold">
            {{ $log->quantity > 0 ? '+'.$log->quantity : $log->quantity }}
          </td>
          <td style="font-size:13px; color:var(--text-muted);">{{ $log->reason }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-muted">No transactions recorded yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ═══ CREDIT ADJUSTMENT MODAL ═══ --}}
<div id="creditAdjustModal" class="modal-backdrop">
  <div class="modal" style="max-width: 480px;">
    <div class="modal-header">
      <h3 class="font-semibold text-serif">Quick Credit Adjustment</h3>
      <button class="modal-close" onclick="hideModal('creditAdjustModal')">×</button>
    </div>
    <form method="POST" action="{{ route('admin.credits.adjust') }}" id="adjustmentForm" onsubmit="return processForm()">
      @csrf
      <input type="hidden" name="quantity" id="finalQuantity">
      <div class="modal-body">
        <div class="form-group mb-3">
          <label class="form-label" for="adjStudent">Select Student</label>
          <select name="student_id" id="adjStudent" class="form-control" required>
            <option value="">— Select Student —</option>
            @foreach($students as $s)
              <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->course->name ?? 'No Course' }})</option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="adjAction">Action</label>
            <select id="adjAction" class="form-control" required>
              <option value="add">Add (+)</option>
              <option value="deduct">Deduct (-)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="adjAmount">Amount</label>
            <input type="number" id="adjAmount" class="form-control" min="1" value="1" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="adjReason">Reason / Notes</label>
          <select name="reason" id="adjReason" class="form-control" required>
            <option value="Package Purchased">Package Purchased</option>
            <option value="Class Compensation">Class Compensation</option>
            <option value="Manual Correction">Manual Correction</option>
            <option value="Referral Program">Referral Program</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideModal('creditAdjustModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Apply Adjustment</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
  let dtBalances = null;
  let dtTransactions = null;

  document.addEventListener("DOMContentLoaded", () => {
    dtBalances = setupDataTable("balancesTable");
    dtTransactions = setupDataTable("transactionsTable", { order: [[0, 'desc']] });
  });

  function openQuickAdjust(studentId, action) {
    document.getElementById("adjustmentForm").reset();
    document.getElementById("adjStudent").value = studentId;
    document.getElementById("adjAction").value  = action;
    document.getElementById("adjAmount").value  = 1;
    showModal('creditAdjustModal');
  }

  function processForm() {
    const action = document.getElementById("adjAction").value;
    const amount = parseInt(document.getElementById("adjAmount").value) || 0;
    const qtyInput = document.getElementById("finalQuantity");
    
    qtyInput.value = (action === 'add') ? amount : -amount;
    return true;
  }
</script>
@endpush
