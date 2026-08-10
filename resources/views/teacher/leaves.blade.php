@extends('layouts.main')
@section('title', 'Leave Management - Harita Music Academy')
@section('page', 'leaves')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
@if(session('success'))
    <div style="background: var(--success-bg); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-3 gap-4">
    <!-- LEAVE APPLY FORM (Teacher Only) -->
    <div class="card" id="leaveApplySection" data-role-limit="teacher">
        <div class="card-header">
            <h4 class="font-semibold">Submit Leave Request</h4>
        </div>
        <form method="POST" action="{{ route('teacher.leaves.store') }}" class="card-body">
            @csrf
            <div class="grid grid-2 gap-2">
                <div class="form-group mb-3">
                    <label class="form-label" for="leaveStart">Start Date</label>
                    <input type="date" name="from_date" id="leaveStart" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="leaveEnd">End Date</label>
                    <input type="date" name="to_date" id="leaveEnd" class="form-control" required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="leaveCover">Cover Teacher (Optional)</label>
                <select name="cover_teacher" id="leaveCover" class="form-control">
                    <option value="">No Cover Needed</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->name }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" for="leaveReason">Reason / Notes</label>
                <textarea name="reason" id="leaveReason" class="form-control" placeholder="e.g. Health checkup, concert..." rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
        </form>
    </div>

    <!-- LEAVES REGISTER LIST -->
    <div class="card grid-2-col-span-2" style="grid-column: span 2;" id="leaveRegisterSection">
        <div class="card-header">
            <h4 class="font-semibold">Leave Log Registry</h4>
        </div>
        <div class="card-body p-3">
            <table class="table display responsive nowrap" id="leavesTable" style="width:100%">
                <thead>
                    <tr>
                        <th data-priority="1">Teacher</th>
                        <th data-priority="3">Dates</th>
                        <th data-priority="4">Reason</th>
                        <th data-priority="5">Cover Teacher</th>
                        <th data-priority="1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                    @php
                        $start = $leave->from_date;
                        $end = $leave->to_date;
                    @endphp
                    <tr>
                        <td class="font-semibold d-flex align-center">
                            <span class="table-avatar">{{ substr(auth()->user()->teacher->name, 0, 1) }}</span>
                            {{ auth()->user()->teacher->name }}
                        </td>
                        <td>
                            @if($start && $end)
                                {{ $start->format('M d, Y') }} to {{ $end->format('M d, Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="max-width: 250px; white-space: normal;">{{ $leave->reason }}</td>
                        <td>{{ $leave->cover_teacher ?? 'None' }}</td>
                        <td>
                            @if($leave->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($leave->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($leave->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
        "order": [[1, "desc"]],
        "responsive": true,
        "language": {
            "search": "",
            "searchPlaceholder": "Search..."
        }
    });
});
</script>
@endpush
