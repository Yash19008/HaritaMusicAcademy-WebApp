@extends('layouts.main')
@section('title', 'Leave Management - Harita Music Academy')
@section('page', 'leaves')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')


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

            <div id="potentialLossContainer" style="display: none; background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-bottom: 1rem; border-radius: 4px;">
                <strong style="color: #856404;">Potential Loss:</strong> 
                <span id="potentialLossAmount" style="color: #856404; font-weight: bold;">₹0</span>
                <div style="font-size: 0.8rem; color: #856404; margin-top: 4px;" id="potentialLossDetails"></div>
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
        order: [[1, 'desc']],
        responsive: true,
        "language": {
            "search": "",
            "searchPlaceholder": "Search..."
        }
    });

    const leaveStart = document.getElementById('leaveStart');
    const leaveEnd = document.getElementById('leaveEnd');
    const lossContainer = document.getElementById('potentialLossContainer');
    const lossAmount = document.getElementById('potentialLossAmount');
    const lossDetails = document.getElementById('potentialLossDetails');

    function calculateLoss() {
        const start = leaveStart.value;
        const end = leaveEnd.value;

        if (start && end) {
            if (new Date(start) > new Date(end)) {
                lossContainer.style.display = 'none';
                return;
            }

            fetch(`{{ route('teacher.leaves.loss') }}?start=${start}&end=${end}`)
                .then(res => res.json())
                .then(data => {
                    if (data.classes > 0) {
                        lossAmount.innerText = `₹${data.loss}`;
                        lossDetails.innerText = `(Rate per class ₹${data.rate} × ${data.classes} possible classes)`;
                        lossContainer.style.display = 'block';
                    } else {
                        lossAmount.innerText = `₹0`;
                        lossDetails.innerText = `(No scheduled classes found in this date range)`;
                        lossContainer.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Error calculating loss:', err);
                    lossContainer.style.display = 'none';
                });
        } else {
            lossContainer.style.display = 'none';
        }
    }

    leaveStart.addEventListener('change', calculateLoss);
    leaveEnd.addEventListener('change', calculateLoss);
  });
</script>
@endpush
