@extends('layouts.main')
@section('page', 'sales')

@push('styles')
<!-- jQuery & DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
  .sales-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
  }

  @media (max-width: 992px) {
    .sales-stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 576px) {
    .sales-stat-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<!-- Sales Stats Grid -->
<div class="sales-stat-grid">
  <div class="card p-3 d-flex align-center gap-3">
    <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">📈</div>
    <div>
      <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Sales (Gross)</div>
      <h3 id="grossSalesText" class="font-bold">₹{{ number_format($grossSales) }}</h3>
    </div>
  </div>
  <div class="card p-3 d-flex align-center gap-3">
    <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">📦</div>
    <div>
      <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Student Enrolled</div>
      <h3 id="packagesSoldText" class="font-bold">{{ $enrolled }}</h3>
    </div>
  </div>
  <div class="card p-3 d-flex align-center gap-3">
    <div class="stat-icon" style="background-color: var(--warning-bg); color: var(--warning)">⚖️</div>
    <div>
      <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Average Transaction</div>
      <h3 id="avgTxText" class="font-bold">₹{{ number_format($avgTx) }}</h3>
    </div>
  </div>
  <div class="card p-3 d-flex align-center gap-3">
    <div class="stat-icon" style="background-color: var(--primary-bg); color: var(--primary)">💰</div>
    <div>
      <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Pending Leads</div>
      <h3 class="font-bold">{{ $leads->where('status', 'pending')->count() }}</h3>
    </div>
  </div>
</div>

<!-- Financial Chart -->
<div class="card mb-4">
  <div class="card-header">
    <h4 class="font-semibold">Revenue Trend Analysis (INR)</h4>
  </div>
  <div class="card-body d-flex justify-center align-center">
    <canvas id="salesLineChart" style="width: 100%; height: 220px;"></canvas>
  </div>
</div>

<!-- Demo Leads & Inquiries Table -->
<div class="card mb-4">
  <div class="card-header d-flex align-center justify-between">
    <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Demo Leads & Inquiries Pipeline</h4>
    <input type="text" id="leadsSearch" class="form-control" placeholder="Search leads..." style="width: 180px;">
  </div>
  <div class="card-body p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
    <table class="table display responsive nowrap" id="leadsTable" style="width:100%">
      <thead>
        <tr>
          <th data-priority="8">Lead ID</th>
          <th data-priority="1">Student Name</th>
          <th data-priority="7">Contact Information</th>
          <th data-priority="6">Instrument Focus</th>
          <th data-priority="3">Amount</th>
          <th data-priority="4">Payment Mode</th>
          <th data-priority="5">Transaction Date</th>
          <th data-priority="2">Status</th>
          <th data-priority="1" style="width: 80px; text-align: center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($leads as $lead)
        @php
          $contacts = $lead->contact ? explode('|', $lead->contact) : ['', ''];
          $email = $contacts[0] ?? '';
          $phone = $contacts[1] ?? '';
        @endphp
        <tr data-lead-id="{{ $lead->id }}" 
            data-lead-name="{{ $lead->student_name ?? '' }}" 
            data-lead-email="{{ $email }}" 
            data-lead-phone="{{ $phone }}" 
            data-lead-instrument="{{ $lead->instrument ?? '' }}">
          <td class="font-bold text-primary">{{ $lead->id }}</td>
          <td class="font-semibold">{{ $lead->student_name ?? '-' }}</td>
          <td>
            @if($phone || $email)
              <div style="font-size:0.8rem; font-weight:500;">{{ $phone ?: '-' }}</div>
              <div class="text-muted" style="font-size:0.75rem;">{{ $email ?: '-' }}</div>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td class="font-medium">{{ $lead->instrument ?? '-' }}</td>
          <td class="font-bold">
            @if($lead->amount)
              ₹{{ number_format($lead->amount) }}
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @if($lead->payment_mode)
              <span class="badge badge-info">{{ $lead->payment_mode }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @if($lead->transaction_date)
              {{ \Carbon\Carbon::parse($lead->transaction_date)->format('Y-m-d') }}
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            @php
              $statusDisplay = $lead->status;
              $badgeClass = 'badge-info';
              
              // Check if there's a demo booking for this lead
              if($lead->latestDemo) {
                $demoStatus = $lead->latestDemo->status;
                
                if($demoStatus === 'scheduled') {
                  $statusDisplay = 'Demo Scheduled';
                  $badgeClass = 'badge-info';
                } elseif($demoStatus === 'completed') {
                  $statusDisplay = 'Demo Completed';
                  $badgeClass = 'badge-success';
                } elseif($demoStatus === 'converted') {
                  $statusDisplay = 'Converted to Student';
                  $badgeClass = 'badge-success';
                } elseif($demoStatus === 'cancelled') {
                  $statusDisplay = 'Demo Cancelled';
                  $badgeClass = 'badge-danger';
                } elseif($demoStatus === 'no-show') {
                  $statusDisplay = 'No Show';
                  $badgeClass = 'badge-danger';
                }
              } else {
                // No demo booking, use payment status
                if($lead->status === 'pending') {
                  $statusDisplay = 'Inquiry';
                  $badgeClass = 'badge-warning';
                } elseif($lead->status === 'confirmed') {
                  $statusDisplay = 'Confirmed';
                  $badgeClass = 'badge-success';
                } elseif($lead->status === 'converted') {
                  $statusDisplay = 'Converted to Student';
                  $badgeClass = 'badge-success';
                } elseif($lead->status === 'cancelled') {
                  $statusDisplay = 'Demo Failed';
                  $badgeClass = 'badge-danger';
                } else {
                  $statusDisplay = ucfirst($lead->status);
                }
              }
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $statusDisplay }}</span>
          </td>
          <td style="text-align: center; overflow: visible;">
            <div class="actions-dropdown-container">
              <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event, this)">⋮</button>
              <div class="actions-dropdown-menu" style="min-width: 170px; right: 0; top: 100%; z-index: 50;">
                <button class="actions-dropdown-item" onclick="openBookDemoModal({{ $lead->id }})">📅 Book Demo</button>
                <button class="actions-dropdown-item" style="color: var(--success);" onclick="openConvertModal({{ $lead->id }})">👤 Convert to Student</button>
                <button class="actions-dropdown-item text-danger" onclick="updateLeadStatus({{ $lead->id }}, 'cancelled')">❌ Demo Failed</button>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Developed by Sitesoch footer -->
<footer class="footer">
  <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
</footer>
@endsection

@push('modals')
<!-- BOOK DEMO MODAL -->
<div id="bookDemoModal" class="modal-backdrop">
  <div class="modal" style="max-width: 480px;">
    <div class="modal-header">
      <h3 class="font-semibold text-serif">Schedule Demo Session</h3>
      <button class="modal-close" onclick="hideModal('bookDemoModal')">×</button>
    </div>
    <form id="bookDemoForm" action="{{ route('admin.demos.store') }}" method="POST">
      @csrf
      <input type="hidden" id="demoLeadId" name="lead_id">
      <div class="modal-body">
        <div class="form-group mb-3">
          <label class="form-label" for="demoStudentName">Student Name</label>
          <input type="text" id="demoStudentName" name="student_name" class="form-control" readonly>
        </div>
        <div class="form-group mb-3">
          <label class="form-label" for="demoInstrument">Instrument Focus</label>
          <input type="text" id="demoInstrument" name="instrument" class="form-control" readonly>
        </div>
        <div class="form-group mb-3">
          <label class="form-label" for="demoTeacher">Assign Mentor</label>
          <select id="demoTeacher" name="teacher_id" class="form-control" required>
            <option value="">Select Teacher</option>
            @foreach($teachers as $teacher)
              <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="demoDateTime">Date & Time</label>
            <input type="datetime-local" id="demoDateTime" name="scheduled_at" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="demoDuration">Duration</label>
            <select id="demoDuration" name="duration_minutes" class="form-control" required>
              <option value="45">45 Mins (Standard)</option>
              <option value="60">60 Mins (Extended)</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideModal('bookDemoModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Book Demo</button>
      </div>
    </form>
  </div>
</div>

<!-- CONVERT TO STUDENT MODAL -->
<div id="convertToStudentModal" class="modal-backdrop">
  <div class="modal" style="max-width: 720px;">
    <div class="modal-header">
      <h3 class="font-semibold text-serif">Convert Lead to Student</h3>
      <button class="modal-close" onclick="hideModal('convertToStudentModal')">×</button>
    </div>
    <form id="convertForm" method="POST" onsubmit="return validateConvertForm()">
      @csrf
      <input type="hidden" id="convertLeadId" name="lead_id">
      <div class="modal-body">
        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="convertCode">Student ID Code</label>
            <input type="text" id="convertCode" class="form-control" readonly value="AUTO">
          </div>
          <div class="form-group">
            <label class="form-label" for="convertName">Full Name</label>
            <input type="text" id="convertName" name="name" class="form-control" required>
          </div>
        </div>

        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="convertEmail">Email Address</label>
            <input type="email" id="convertEmail" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="convertPhone">Phone Number</label>
            <input type="text" id="convertPhone" name="phone" class="form-control" required>
          </div>
        </div>

        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="convertLevel">Initial Level</label>
            <select id="convertLevel" name="enrolled_level" class="form-control" required>
              <option value="Foundation Level">Foundation Level</option>
              <option value="Intermediate Level">Intermediate Level</option>
              <option value="Advanced Level">Advanced Level</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="convertInstrument">Instrument Category</label>
            <select id="convertInstrument" name="course_id" class="form-control" required>
              @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="convertTeacher">Assigned Teacher</label>
            <select id="convertTeacher" name="teacher_id" class="form-control" required>
              @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="convertPackage">Selected Package</label>
            <select id="convertPackage" name="package_selection" class="form-control" onchange="updatePackageCost()" required>
              <option value="Sitar Advanced 12-Class Package|12000|12">Sitar Advanced (12 classes - ₹12,000)</option>
              <option value="Vocal Basic 10-Class Package|8500|10">Vocal Basic (10 classes - ₹8,500)</option>
              <option value="Violin Starter 5-Class Package|4500|5">Violin Starter (5 classes - ₹4,500)</option>
              <option value="Tabla Intermediate 20-Class Package|16000|20">Tabla Intermediate (20 classes - ₹16,000)</option>
            </select>
          </div>
        </div>

        <div class="grid grid-2 gap-3 mb-3">
          <div class="form-group">
            <label class="form-label" for="convertAmount">Amount Paid (INR)</label>
            <input type="number" id="convertAmount" name="amount" class="form-control" step="0.01" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="convertPayment">Payment Mode</label>
            <select id="convertPayment" name="payment_mode" class="form-control" required>
              <option value="UPI (PHONEPE)">UPI (PhonePe)</option>
              <option value="UPI (GOOGLE PAY)">UPI (Google Pay)</option>
              <option value="CREDIT CARD">Credit Card</option>
              <option value="NET BANKING">Net Banking</option>
              <option value="CASH">Cash</option>
            </select>
          </div>
        </div>
        <input type="hidden" id="convertCredits" name="credits" value="10">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideModal('convertToStudentModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Student & Register Sale</button>
      </div>
    </form>
  </div>
</div>
@endpush

@push('scripts')
<!-- jQuery & DataTables Script dependencies -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  let dtLeads = null;

  document.addEventListener("DOMContentLoaded", () => {
    // Initialize DataTable
    dtLeads = $('#leadsTable').DataTable({
      responsive: true,
      pageLength: 10,
      order: [[0, 'desc']],
      language: {
        search: "",
        searchPlaceholder: "Search...",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "No entries available",
        infoFiltered: "(filtered from _TOTAL_ total entries)",
        zeroRecords: "No matching records found"
      }
    });

    // Bind search input
    $('#leadsSearch').on('keyup', function() {
      dtLeads.search(this.value).draw();
    });

    // Render chart
    renderSalesChart();
  });

  function renderSalesChart() {
    const ctx = document.getElementById('salesLineChart');
    if (!ctx) return;

    const labels = @json($labels);
    const revenue = @json($revenue);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Revenue (₹)',
          data: revenue,
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '₹' + value.toLocaleString('en-IN');
              }
            }
          }
        }
      }
    });
  }

  function openBookDemoModal(leadId) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) return;

    const leadName = row.getAttribute('data-lead-name');
    const leadInstrument = row.getAttribute('data-lead-instrument');

    document.getElementById('demoLeadId').value = leadId;
    document.getElementById('demoStudentName').value = leadName || '';
    document.getElementById('demoInstrument').value = leadInstrument || '';

    const now = new Date();
    now.setHours(now.getHours() + 24);
    document.getElementById('demoDateTime').value = now.toISOString().slice(0, 16);

    showModal('bookDemoModal');
  }

  function openConvertModal(leadId) {
    console.log('Opening convert modal for lead:', leadId);
    
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) {
      console.error('Row not found for lead:', leadId);
      return;
    }

    const leadName = row.getAttribute('data-lead-name');
    const leadEmail = row.getAttribute('data-lead-email');
    const leadPhone = row.getAttribute('data-lead-phone');
    const leadInstrument = row.getAttribute('data-lead-instrument');

    console.log('Lead data:', { leadName, leadEmail, leadPhone, leadInstrument });

    document.getElementById('convertLeadId').value = leadId;
    document.getElementById('convertName').value = leadName || '';
    document.getElementById('convertEmail').value = leadEmail || '';
    document.getElementById('convertPhone').value = leadPhone || '';

    // Set form action dynamically
    const form = document.getElementById('convertForm');
    form.action = '/admin/sales/' + leadId + '/convert';
    console.log('Form action set to:', form.action);

    // Set default amount for first package
    updatePackageCost();
    
    showModal('convertToStudentModal');
  }

  function updatePackageCost() {
    const pkgVal = document.getElementById('convertPackage').value;
    const parts = pkgVal.split('|');
    if (parts.length > 1) {
      document.getElementById('convertAmount').value = parts[1];
      document.getElementById('convertCredits').value = parts[2] || 10;
    }
  }

  function updateLeadStatus(leadId, status) {
    const statusText = status === 'cancelled' ? 'Demo Failed' : status;
    if (!confirm(`Are you sure you want to mark this lead as "${statusText}"?`)) {
      return;
    }

    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/sales/' + leadId;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    
    const statusField = document.createElement('input');
    statusField.type = 'hidden';
    statusField.name = 'status';
    statusField.value = status;
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    form.appendChild(statusField);
    document.body.appendChild(form);
    form.submit();
  }

  function validateConvertForm() {
    console.log('Validating convert form...');
    
    const form = document.getElementById('convertForm');
    const formData = new FormData(form);
    
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
      console.log(`  ${key}: ${value}`);
    }
    
    const email = document.getElementById('convertEmail').value;
    const phone = document.getElementById('convertPhone').value;
    const name = document.getElementById('convertName').value;

    if (!name || !email || !phone) {
      alert('Please fill in all required fields: Name, Email, and Phone');
      return false;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Please enter a valid email address');
      return false;
    }

    console.log('Form validation passed, submitting to:', form.action);
    return true;
  }
</script>
@endpush
