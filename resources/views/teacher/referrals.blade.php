@extends('layouts.main')
@section('page', 'referrals')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
.referral-grid {
      display: grid;
      grid-template-columns: 1.2fr 1.8fr;
      gap: 1.5rem;
    }

    @media (max-width: 992px) {
      .referral-grid {
        grid-template-columns: 1fr;
      }
    }

    .referral-code-card {
      background: linear-gradient(135deg, rgba(197, 168, 128, 0.08) 0%, rgba(74, 14, 23, 0.05) 100%);
      border: 2px dashed var(--primary);
      border-radius: var(--radius-md);
      padding: 1.5rem;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .referral-code-text {
      font-family: var(--font-serif);
      font-size: 2rem;
      font-weight: bold;
      color: var(--secondary);
      letter-spacing: 2px;
      margin: 0.5rem 0;
    }
</style>
@endpush

@section('content')
<div class="referral-grid">

        <!-- Left Side: Code & Submission Form -->
        <div>
          <!-- Code Card -->
          <div class="referral-code-card">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.1rem; color: var(--text-main);">My Affiliate Code</h4>
            <div class="referral-code-text">HMA-{{ strtoupper(str_replace(' ', '', auth()->user()->name)) }}</div>
            <p class="text-muted mb-0" style="font-size:0.75rem;">Refer new students or teachers. Earn a Rs 500 bonus
              paid directly with your payroll for every approved referral conversion!</p>
          </div>

          <!-- Submit Form -->
          <div class="card">
            <div class="card-header">
              <h4 class="font-semibold">Submit New Referral</h4>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('teacher.referrals.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                  <label class="form-label font-semibold">Name</label>
                  <input type="text" name="referred_name" class="form-control" placeholder="Enter full name" required>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label font-semibold">Email Address</label>
                  <input type="email" name="referred_email" class="form-control" placeholder="eg. jane@example.com" required>
                </div>
                <div class="form-group mb-3">
                  <label class="form-label font-semibold">Referred Person Category</label>
                  <select name="interest_role" class="form-control" required>
                    <option value="student">Student Candidate</option>
                    <option value="teacher">Teacher Candidate</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Referral</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Right Side: Referrals Log -->
        <div class="card">
          <div class="card-header">
            <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">My Referrals History
            </h4>
          </div>
          <div class="card-body p-3">
            <table class="table display responsive nowrap" id="refHistoryTable" style="width:100%">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Referred Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Reward Status</th>
                </tr>
              </thead>
              <tbody id="refHistoryBody">
                @forelse($referrals as $ref)
                <tr>
                  <td>{{ $ref->created_at->format('M d, Y') }}</td>
                  <td>{{ $ref->referred_name }}</td>
                  <td>{{ $ref->referred_email ?? $ref->referred_phone ?? 'N/A' }}</td>
                  <td>{{ $ref->interest_role }}</td>
                  <td>
                    @if($ref->status === 'approved')
                      <span class="badge badge-success">Approved</span>
                    @elseif($ref->status === 'rejected')
                      <span class="badge badge-danger">Rejected</span>
                    @else
                      <span class="badge badge-warning">Pending</span>
                    @endif
                  </td>
                  <td>
                    @if($ref->status === 'approved')
                      <span class="text-success font-semibold">{{ $ref->bonus_reward }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">No referrals submitted yet.</td>
                </tr>
                @endforelse
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
    if ($.fn.DataTable.isDataTable('#refHistoryTable')) {
        $('#refHistoryTable').DataTable().destroy();
    }
    $('#refHistoryTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search history..."
        }
    });
});
</script>
@endpush
