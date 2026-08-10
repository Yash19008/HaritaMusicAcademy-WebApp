@extends('layouts.main')
@section('page', 'sales')

@push('styles')
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
            <h3 class="font-bold">₹{{ number_format($grossSales, 0) }}</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--info-bg); color: var(--info)">📦</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Student Enrolled</div>
            <h3 class="font-bold">{{ $enrolled }}</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--warning-bg); color: var(--warning)">⚖️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Average Transaction</div>
            <h3 class="font-bold">₹{{ number_format($avgTx, 0) }}</h3>
          </div>
        </div>
        <div class="card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--primary-bg); color: var(--primary)">📊</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Total Leads</div>
            <h3 class="font-bold">{{ $leads->count() }}</h3>
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
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Demo Leads &amp; Inquiries Pipeline</h4>
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
              <tr>
                <td class="font-bold text-primary">#{{ $lead->id }}</td>
                <td class="font-semibold">{{ $lead->student_name }}</td>
                <td>
                  <div style="font-size:0.8rem; font-weight:500;">{{ $lead->contact ?? '—' }}</div>
                  <div class="text-muted" style="font-size:0.75rem;">{{ $lead->email ?? '—' }}</div>
                </td>
                <td class="font-medium">{{ $lead->instrument ?? '—' }}</td>
                <td class="font-bold">
                  @if($lead->amount)
                    ₹{{ number_format($lead->amount, 0) }}
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
                <td>{{ $lead->transaction_date ? $lead->transaction_date->format('Y-m-d') : '—' }}</td>
                <td>
                  @php
                    $badgeClass = 'badge-info';
                    if($lead->status === 'pending') $badgeClass = 'badge-warning';
                    elseif($lead->status === 'confirmed' || $lead->status === 'converted') $badgeClass = 'badge-success';
                    elseif($lead->status === 'cancelled') $badgeClass = 'badge-danger';
                  @endphp
                  <span class="badge {{ $badgeClass }}">{{ ucfirst($lead->status) }}</span>
                </td>
                <td style="text-align: center; overflow: visible;">
                  <div class="actions-dropdown-container">
                    <button class="actions-kebab-btn" onclick="toggleActionsDropdown(event, this)">⋮</button>
                    <div class="actions-dropdown-menu" style="min-width: 170px; right: 0; top: 100%; z-index: 50;">
                      <button class="actions-dropdown-item" onclick="openConvertModal({{ $lead->id }}, '{{ $lead->student_name }}', '{{ $lead->contact }}', '{{ $lead->instrument }}')">👤 Convert to Student</button>
                      <button class="actions-dropdown-item" onclick="openEditModal({{ $lead->id }}, '{{ $lead->status }}', '{{ $lead->payment_mode }}', {{ $lead->amount ?? 0 }}, '{{ $lead->transaction_date ? $lead->transaction_date->format('Y-m-d') : '' }}')">✏️ Edit Lead</button>
                      <form action="{{ route('admin.sales.destroy', $lead->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this lead?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="actions-dropdown-item text-danger" style="border:none; background:none; width:100%; text-align:left;">🗑️ Delete Lead</button>
                      </form>
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
<!-- Convert to Student Modal -->
<div id="convertToStudentModal" class="modal-backdrop" style="display: none;">
    <div class="modal" style="max-width: 720px;">
      <div class="modal-header">
        <h3 class="font-semibold text-serif">Convert Lead to Student</h3>
        <button class="modal-close" onclick="hideModal('convertToStudentModal')">×</button>
      </div>
      <form id="convertForm" action="" method="POST">
        @csrf
        <input type="hidden" id="convertLeadId">
        <div class="modal-body">
          <div class="grid grid-2 gap-3 mb-3">
            <div class="form-group">
              <label class="form-label" for="convertName">Full Name</label>
              <input type="text" name="name" id="convertName" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="convertEmail">Email Address</label>
              <input type="email" name="email" id="convertEmail" class="form-control" required>
            </div>
          </div>

          <div class="grid grid-2 gap-3 mb-3">
            <div class="form-group">
              <label class="form-label" for="convertPhone">Phone Number</label>
              <input type="text" name="phone" id="convertPhone" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="convertLevel">Initial Level</label>
              <select name="enrolled_level" id="convertLevel" class="form-control" required>
                <option value="Foundation Level">Foundation Level</option>
                <option value="Intermediate Level">Intermediate Level</option>
                <option value="Advanced Level">Advanced Level</option>
              </select>
            </div>
          </div>

          <div class="grid grid-2 gap-3 mb-3">
            <div class="form-group">
              <label class="form-label" for="convertCourse">Course</label>
              <select name="course_id" id="convertCourse" class="form-control" required>
                <option value="">Select Course</option>
                @foreach($courses as $course)
                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="convertTeacher">Assign Teacher</label>
              <select name="teacher_id" id="convertTeacher" class="form-control" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                  <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="grid grid-2 gap-3 mb-3">
            <div class="form-group">
              <label class="form-label" for="convertCredits">Credits to Assign</label>
              <input type="number" name="credits" id="convertCredits" class="form-control" min="1" value="10" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="convertAmount">Payment Amount (₹)</label>
              <input type="number" name="amount" id="convertAmount" class="form-control" min="0" step="0.01" required>
            </div>
          </div>

          <div class="form-group mb-3">
            <label class="form-label" for="convertPaymentMode">Payment Mode</label>
            <select name="payment_mode" id="convertPaymentMode" class="form-control" required>
              <option value="Cash">Cash</option>
              <option value="UPI">UPI</option>
              <option value="Card">Card</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="hideModal('convertToStudentModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Convert to Student</button>
        </div>
      </form>
    </div>
</div>

<!-- Edit Lead Modal -->
<div id="editLeadModal" class="modal-backdrop" style="display: none;">
    <div class="modal" style="max-width: 480px;">
      <div class="modal-header">
        <h3 class="font-semibold text-serif">Edit Lead</h3>
        <button class="modal-close" onclick="hideModal('editLeadModal')">×</button>
      </div>
      <form id="editForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group mb-3">
            <label class="form-label" for="editStatus">Status</label>
            <select name="status" id="editStatus" class="form-control" required>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="editPaymentMode">Payment Mode</label>
            <select name="payment_mode" id="editPaymentMode" class="form-control">
              <option value="">Not Set</option>
              <option value="Cash">Cash</option>
              <option value="UPI">UPI</option>
              <option value="Card">Card</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="editAmount">Amount (₹)</label>
            <input type="number" name="amount" id="editAmount" class="form-control" min="0" step="0.01">
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="editTransactionDate">Transaction Date</label>
            <input type="date" name="transaction_date" id="editTransactionDate" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="hideModal('editLeadModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Lead</button>
        </div>
      </form>
    </div>
</div>
@endpush

@push('scripts')
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
      searchPlaceholder: "Search all columns..."
    }
  });

  // Bind external search box
  $('#leadsSearch').on('input', function() {
    dtLeads.search(this.value).draw();
  });

  // Render revenue chart
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
        tension: 0.3,
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

function openConvertModal(id, name, contact, instrument) {
  document.getElementById('convertLeadId').value = id;
  document.getElementById('convertName').value = name || '';
  document.getElementById('convertPhone').value = contact || '';
  document.getElementById('convertForm').action = '/admin/sales/' + id + '/convert';
  showModal('convertToStudentModal');
}

function openEditModal(id, status, paymentMode, amount, transactionDate) {
  document.getElementById('editStatus').value = status || 'pending';
  document.getElementById('editPaymentMode').value = paymentMode || '';
  document.getElementById('editAmount').value = amount || '';
  document.getElementById('editTransactionDate').value = transactionDate || '';
  document.getElementById('editForm').action = '/admin/sales/' + id;
  showModal('editLeadModal');
}

function showModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'flex';
  }
}

function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'none';
  }
}

function toggleActionsDropdown(event, button) {
  event.stopPropagation();
  
  // Close all other dropdowns
  document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
    if (menu !== button.nextElementSibling) {
      menu.classList.remove('show');
    }
  });
  
  // Toggle current dropdown
  const menu = button.nextElementSibling;
  menu.classList.toggle('show');
}

// Close dropdowns when clicking outside
document.addEventListener('click', () => {
  document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
    menu.classList.remove('show');
  });
});
</script>
@endpush
