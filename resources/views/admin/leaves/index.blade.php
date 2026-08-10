@extends('layouts.main')
@section('title', 'Leave Management')
@section('page', 'leaves')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')

@if(session('success'))
  <div style="padding:.75rem 1rem;margin-bottom:1rem;background:#ecfdf5;color:#059669;border-radius:var(--radius-sm);font-size:13.5px;">✅ {{ session('success') }}</div>
@endif

<div class="card mb-4">
  <div class="card-header d-flex justify-between align-center">
    <h4 class="font-semibold text-serif">Leave Log Registry</h4>
  </div>
  <div class="card-body p-3" style="overflow-x:auto;">
    <table class="table display responsive nowrap" id="leavesTable" style="width:100%">
      <thead>
        <tr>
          <th data-priority="1">Teacher</th>
          <th data-priority="3">Dates</th>
          <th data-priority="4">Reason</th>
          <th data-priority="5">Cover Teacher</th>
          <th data-priority="2">Status</th>
          <th data-priority="1" style="text-align:center;">Approval Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leaves as $leave)
        <tr>
          <td class="font-semibold">{{ $leave->teacher->name ?? 'Unknown Teacher' }}</td>
          <td style="font-size:13px;">
            {{ \Carbon\Carbon::parse($leave->from_date)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($leave->to_date)->format('M d, Y') }}
          </td>
          <td style="font-size:13.5px; color:var(--text-main);">{{ $leave->reason }}</td>
          <td>{{ $leave->cover_teacher ?: 'No Cover Needed' }}</td>
          <td>
            @php $st = strtolower($leave->status); @endphp
            <span class="badge {{ $st === 'approved' ? 'badge-success' : ($st === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
              {{ ucfirst($st) }}
            </span>
          </td>
          <td style="text-align:center;">
            @if($st === 'pending')
              <div class="actions-dropdown-container" style="display:inline-block;">
                <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event, this)">⋮</button>
                <div class="actions-dropdown-menu" style="min-width:140px;">
                  <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="actions-dropdown-item text-success" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">✅ Approve</button>
                  </form>
                  <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}" style="margin:0;" onsubmit="return confirm('Are you sure you want to reject this leave?');">
                    @csrf
                    <button type="submit" class="actions-dropdown-item text-danger" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.5rem .75rem;">❌ Reject</button>
                  </form>
                </div>
              </div>
            @else
              <span class="text-muted" style="font-size:12px;">No Actions</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-4 text-muted">No leave applications found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
  $(document).ready(function() {
    $('#leavesTable').DataTable({
        order: [[1, 'desc']],
        responsive: true
    });
  });
</script>
@endpush
