@extends('layouts.main')
@section('title', 'Referrals')
@section('page', 'referrals')

@section('content')
<!-- Metrics Widgets -->
      <div class="stat-card-grid mb-4">
        <!-- Metric 1: Total Referrals -->
        <div class="card stat-card">
          <div class="card-body p-4 d-flex align-center justify-between">
            <div>
              <span class="stat-card-label">Total Referrals</span>
              <h3 class="font-bold">{{ $total }}</h3>
            </div>
            <div class="stat-card-icon" style="background: rgba(13, 148, 136, 0.08); color: var(--primary);">
              👥
            </div>
          </div>
        </div>
        <!-- Metric 2: Pending Referrals -->
        <div class="card stat-card">
          <div class="card-body p-4 d-flex align-center justify-between">
            <div>
              <span class="stat-card-label">Pending Conversions</span>
              <h3 class="font-bold">{{ $pending }}</h3>
            </div>
            <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.08); color: var(--warning);">
              ⏳
            </div>
          </div>
        </div>
        <!-- Metric 3: Approved Referrals -->
        <div class="card stat-card">
          <div class="card-body p-4 d-flex align-center justify-between">
            <div>
              <span class="stat-card-label">Successful Signups</span>
              <h3 class="font-bold">{{ $approved }}</h3>
            </div>
            <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.08); color: var(--success);">
              ✅
            </div>
          </div>
        </div>
        <!-- Metric 4: Conversion Rate -->
        <div class="card stat-card">
          <div class="card-body p-4 d-flex align-center justify-between">
            <div>
              <span class="stat-card-label">Conversion Rate</span>
              <h3 class="font-bold">{{ $rate }}%</h3>
            </div>
            <div class="stat-card-icon" style="background: rgba(99, 102, 241, 0.08); color: var(--secondary);">
              📊
            </div>
          </div>
        </div>
      </div>

      <!-- Referrals Table Wrapper -->
      <div class="card p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <h4 class="font-semibold mb-3 text-serif">Submitted Referral Applications</h4>
        <table class="table display responsive nowrap" id="referralsTable" style="width:100%">
          <thead>
            <tr>
              <th data-priority="1">ID</th>
              <th data-priority="1">Referrer Name</th>
              <th data-priority="3">Referrer Role</th>
              <th data-priority="2">Referred Friend</th>
              <th data-priority="4">Friend's Email</th>
              <th data-priority="5">Interest Role</th>
              <th data-priority="6">Date Applied</th>
              <th data-priority="7">Bonus Reward</th>
              <th data-priority="2">Status</th>
              <th data-priority="1" style="width: 140px; text-align: center;">Actions</th>
            </tr>
          </thead>
          <tbody id="referralsTableBody">
            @forelse($referrals as $ref)
            <tr>
              <td>{{ $ref->id }}</td>
              <td>{{ $ref->referrer->name ?? 'Unknown' }}</td>
              <td><span class="badge badge-info" style="text-transform: capitalize;">{{ $ref->referrer_role }}</span></td>
              <td>{{ $ref->referred_name }}</td>
              <td>{{ $ref->referred_email ?? 'N/A' }}</td>
              <td>{{ $ref->interest_role ?? 'N/A' }}</td>
              <td>{{ $ref->created_at->format('M d, Y') }}</td>
              <td>{{ $ref->bonus_reward ?? 'N/A' }}</td>
              <td>
                @if($ref->status === 'approved')
                  <span class="badge badge-success">Approved</span>
                @elseif($ref->status === 'rejected')
                  <span class="badge badge-danger">Rejected</span>
                @else
                  <span class="badge badge-warning">Pending</span>
                @endif
              </td>
              <td style="text-align: center;">
                @if($ref->status === 'pending')
                  <div class="d-flex gap-2 justify-center">
                    <form action="{{ route('admin.referrals.update', $ref->id) }}" method="POST" style="margin:0;">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="approved">
                      <button type="submit" class="badge badge-success" style="border:none; cursor:pointer;" onclick="return confirm('Approve referral? If this is a student, they will automatically be granted their free class credits.')">Approve</button>
                    </form>
                    <form action="{{ route('admin.referrals.update', $ref->id) }}" method="POST" style="margin:0;">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="rejected">
                      <button type="submit" class="badge badge-danger" style="border:none; cursor:pointer;" onclick="return confirm('Reject this referral?')">Reject</button>
                    </form>
                  </div>
                @else
                  <span class="text-muted" style="font-size: 0.8rem;">Processed</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center py-4 text-muted">No referrals found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    $('#referralsTable').DataTable({
        responsive: true,
        order: [[6, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search referrals..."
        }
    });
});
</script>
@endpush
