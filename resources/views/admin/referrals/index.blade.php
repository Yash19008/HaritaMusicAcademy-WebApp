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
                    <th data-priority="1">#</th>
                    <th data-priority="2">Referral Code</th>
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
                @foreach ($referrals as $ref)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span
                                style="font-family: monospace; font-weight: bold; background: rgba(81, 4, 14, 0.05); padding: 0.2rem 0.5rem; border-radius: 4px; color: var(--primary);">HMA-{{ strtoupper(str_replace(' ', '', $ref->referrer->name ?? 'UNKNOWN')) }}</span>
                        </td>
                        <td><span class="badge badge-info"
                                style="text-transform: capitalize;">{{ $ref->referrer_role }}</span></td>
                        <td>{{ $ref->referred_name }}</td>
                        <td>{{ $ref->referred_email ?? 'N/A' }}</td>
                        <td>{{ $ref->interest_role ?? 'N/A' }}</td>
                        <td>{{ $ref->created_at->format('M d, Y') }}</td>
                        <td>{{ $ref->bonus_reward ?? 'N/A' }}</td>
                        <td>
                            @if ($ref->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($ref->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($ref->status === 'pending')
                                <div class="d-flex gap-2 justify-center">
                                    <button type="button" class="badge badge-success" style="border:none; cursor:pointer;"
                                        onclick="openActionModal('{{ route('admin.referrals.update', $ref->id) }}', 'approved')">Approve</button>
                                    <button type="button" class="badge badge-danger" style="border:none; cursor:pointer;"
                                        onclick="openActionModal('{{ route('admin.referrals.update', $ref->id) }}', 'rejected')">Reject</button>
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 0.8rem;">Processed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Custom Action Modal -->
    <div id="actionModal"
        style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div class="card slide-up"
            style="width:100%; max-width:450px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 10px 25px rgba(0,0,0,0.2); text-align:center; position:relative;">
            <button type="button" onclick="closeActionModal()"
                style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.8rem; cursor:pointer; color:#888; line-height:1;">&times;</button>

            <div id="actionIcon" style="font-size:3.5rem; margin-bottom:1rem;">✅</div>
            <h3 id="actionTitle"
                style="font-family: var(--font-serif); font-size:1.6rem; font-weight:700; margin-bottom:0.8rem; color:var(--text-main);">
                Confirm Action</h3>
            <p id="actionDesc" style="color:var(--text-muted); font-size:0.95rem; margin-bottom:2rem; line-height:1.6;">Are
                you sure?</p>

            <form id="actionForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="actionStatusInput">
                <div class="d-flex gap-3 justify-center">
                    <button type="button" class="btn"
                        style="background:#f1f5f9; color:#475569; padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; border: 1px solid #cbd5e1;"
                        onclick="closeActionModal()">Cancel</button>
                    <button type="submit" id="actionSubmitBtn" class="btn btn-primary"
                        style="padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Confirm</button>
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
        $(document).ready(function() {
            $('#referralsTable').DataTable({
                responsive: true,
                order: [
                    [6, 'desc']
                ], // 6 is Date Applied index
                language: {
                    search: "",
                    searchPlaceholder: "Search referrals..."
                }
            });
        });

        function openActionModal(actionUrl, type) {
            const modal = document.getElementById('actionModal');
            const form = document.getElementById('actionForm');
            const statusInput = document.getElementById('actionStatusInput');
            const title = document.getElementById('actionTitle');
            const desc = document.getElementById('actionDesc');
            const icon = document.getElementById('actionIcon');
            const btn = document.getElementById('actionSubmitBtn');

            form.action = actionUrl;
            statusInput.value = type;

            if (type === 'approved') {
                title.innerText = 'Approve Referral';
                desc.innerText =
                    'Are you sure you want to approve this referral? If the referrer is a student, their account will automatically be credited with a free bonus class! This will immediately email the participants.';
                icon.innerText = '🎉';
                btn.innerText = 'Approve Referral';
                btn.style.background = 'var(--success)';
                btn.style.borderColor = 'var(--success)';
                btn.style.color = '#fff';
            } else {
                title.innerText = 'Reject Referral';
                desc.innerText =
                    'Are you sure you want to reject this referral application? This action cannot be undone and no bonus credits will be awarded to the referrer.';
                icon.innerText = '⚠️';
                btn.innerText = 'Reject Referral';
                btn.style.background = 'var(--danger)';
                btn.style.borderColor = 'var(--danger)';
                btn.style.color = '#fff';
            }

            modal.style.display = 'flex';
        }

        function closeActionModal() {
            document.getElementById('actionModal').style.display = 'none';
        }
    </script>
@endpush
