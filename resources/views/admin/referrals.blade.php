@extends('layouts.main')
@section('page', 'referrals')

@section('content')
<!-- Metrics Widgets -->
      <div class="stat-card-grid mb-4">
        <!-- Metric 1: Total Referrals -->
        <div class="card stat-card">
          <div class="card-body p-4 d-flex align-center justify-between">
            <div>
              <span class="stat-card-label">Total Referrals</span>
              <h3 id="statTotalRefs" class="font-bold">0</h3>
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
              <h3 id="statPendingRefs" class="font-bold">0</h3>
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
              <h3 id="statApprovedRefs" class="font-bold">0</h3>
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
              <h3 id="statConversionRate" class="font-bold">0%</h3>
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
            <!-- Dynamically populated -->
          </tbody>
        </table>
      </div>
@endsection
