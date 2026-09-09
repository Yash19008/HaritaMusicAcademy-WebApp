@extends('layouts.main')
@section('title', 'Demo Classes')
@section('page', 'demos')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
.stat-card-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .stat-card-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .stat-card-grid {
        grid-template-columns: 1fr;
      }
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .badge-select {
      font-size: 11.5px;
      font-weight: 600;
      padding: 0.25rem 0.5rem;
      border-radius: var(--radius-sm);
      outline: none;
      transition: all 0.2s;
      cursor: pointer;
    }
</style>
@endpush

@section('content')


<!-- KPI Stats -->
      <div class="stat-card-grid">
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #eff6ff; color: #1e40af">📅</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Scheduled Demos</div>
            <h3 id="statScheduled" class="font-bold">{{ $demos->where('status', 'scheduled')->count() }}</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: var(--success-bg); color: var(--success)">✔️</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Completed Demos</div>
            <h3 id="statCompleted" class="font-bold">{{ $demos->where('status', 'completed')->count() }}</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #fef3c7; color: #b45309;">👤</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Converted Students</div>
            <h3 id="statConverted" class="font-bold">{{ $demos->where('status', 'converted')->count() }}</h3>
          </div>
        </div>
        <div class="card stat-card p-3 d-flex align-center gap-3">
          <div class="stat-icon" style="background-color: #fee2e2; color: #b91c1c;">❌</div>
          <div>
            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Cancelled / No Show</div>
            <h3 id="statCancelled" class="font-bold">{{ $demos->whereIn('status', ['cancelled', 'no-show'])->count() }}</h3>
          </div>
        </div>
      </div>

      <!-- Demo Classes Log -->
      <div class="card">
        <div class="card-header d-flex align-center justify-between flex-wrap gap-2">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Demo Session Ledger</h4>
        </div>
        <div class="card-body p-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
          <table class="table display responsive nowrap" id="demosTable" style="width:100%">
            <thead>
              <tr>
                <th data-priority="7">Demo ID</th>
                <th data-priority="1">Student Name</th>
                <th data-priority="3">Instrument</th>
                <th data-priority="4">Assigned Teacher</th>
                <th data-priority="5">Scheduled Date &amp; Time</th>
                <th data-priority="6">Duration</th>
                <th data-priority="2">Attendance</th>
                <th data-priority="2">Meet Link</th>
                <th data-priority="1">Status (Update Inline)</th>
              </tr>
            </thead>
            <tbody id="demosTableBody">
              @foreach($demos as $demo)
              <tr>
                  <td class="font-bold text-primary">DMO{{ str_pad($demo->id, 3, '0', STR_PAD_LEFT) }}</td>
                  <td class="font-semibold">{{ $demo->student_name }}</td>
                  <td><span class="badge badge-primary">{{ $demo->instrument }}</span></td>
                  <td>{{ $demo->teacher->user->name ?? 'N/A' }}</td>
                  <td>{{ $demo->scheduled_at->format('M d, Y h:i A') }}</td>
                  <td>{{ $demo->duration_minutes }} mins</td>
                  <td>
                    <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 0.75rem; color: var(--text-muted); width: 45px;">Teacher:</span>
                            <select class="form-control" style="padding: 2px 4px; font-size: 0.75rem; width: auto; display: inline-block; border-color: #e2e8f0; border-radius: 4px;" onchange="updateAttendance({{ $demo->id }}, 'teacher_attended', this.value)">
                                <option value="">—</option>
                                <option value="1" {{ $demo->teacher_attended === true ? 'selected' : '' }}>Present</option>
                                <option value="0" {{ $demo->teacher_attended === false ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size: 0.75rem; color: var(--text-muted); width: 45px;">Student:</span>
                            <select class="form-control" style="padding: 2px 4px; font-size: 0.75rem; width: auto; display: inline-block; border-color: #e2e8f0; border-radius: 4px;" onchange="updateAttendance({{ $demo->id }}, 'student_attended', this.value)">
                                <option value="">—</option>
                                <option value="1" {{ $demo->student_attended === true ? 'selected' : '' }}>Present</option>
                                <option value="0" {{ $demo->student_attended === false ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>
                    </div>
                  </td>
                  <td>
                    @if($demo->google_meet_link)
                      <a href="{{ $demo->google_meet_link }}" target="_blank" class="btn btn-sm" style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                        Join
                      </a>
                    @else
                      <span class="text-muted" style="font-size: 0.75rem;">N/A</span>
                    @endif
                  </td>
                  <td>
                    <form onsubmit="event.preventDefault();" style="display: inline;">
                        @csrf
                        <select name="status" class="form-control badge-select" onchange="updateDemoStatus(this, '{{ route('admin.demos.status', $demo) }}')" style="
                            @if($demo->status == 'scheduled') background-color: #eff6ff; color: #1e40af; border: 1px solid #3b82f6;
                            @elseif($demo->status == 'completed') background-color: var(--success-bg); color: var(--success); border: 1px solid var(--success);
                            @elseif($demo->status == 'converted') background-color: #fef3c7; color: #b45309; border: 1px solid #f59e0b;
                            @elseif($demo->status == 'cancelled') background-color: #f3f4f6; color: #374151; border: 1px solid #9ca3af;
                            @elseif($demo->status == 'no-show') background-color: #fee2e2; color: #b91c1c; border: 1px solid #ef4444;
                            @endif
                        ">
                            <option value="scheduled" {{ $demo->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $demo->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="converted" {{ $demo->status == 'converted' ? 'selected' : '' }}>Converted</option>
                            <option value="cancelled" {{ $demo->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no-show" {{ $demo->status == 'no-show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </form>
                    @if($demo->status === 'converted' && $demo->convertedStudent)
                        <div style="font-size:10px; margin-top:4px; color: #b45309;">
                            Student: {{ $demo->convertedStudent->name ?? 'N/A' }}
                        </div>
                    @endif
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#demosTable').DataTable({
        "order": [[4, "desc"]], // Order by Scheduled Date
        "pageLength": 10,
        "language": {
            "search": "",
            "searchPlaceholder": "Search demos..."
        }
    });
});

function updateDemoStatus(selectElement, url) {
    const status = selectElement.value;
    const form = selectElement.closest('form');
    const token = form.querySelector('input[name="_token"]').value;

    // Apply colors based on selection right away for instant feedback
    const colors = {
        'scheduled': { bg: '#eff6ff', color: '#1e40af', border: '#3b82f6' },
        'completed': { bg: 'var(--success-bg)', color: 'var(--success)', border: 'var(--success)' },
        'converted': { bg: '#fef3c7', color: '#b45309', border: '#f59e0b' },
        'cancelled': { bg: '#f3f4f6', color: '#374151', border: '#9ca3af' },
        'no-show': { bg: '#fee2e2', color: '#b91c1c', border: '#ef4444' }
    };

    if(colors[status]) {
        selectElement.style.backgroundColor = colors[status].bg;
        selectElement.style.color = colors[status].color;
        selectElement.style.border = `1px solid ${colors[status].border}`;
    }

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            console.log(data.message);
            
            // Create a toast notification
            const toast = document.createElement('div');
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.backgroundColor = '#10b981'; // Success green
            toast.style.color = '#ffffff';
            toast.style.padding = '12px 16px 12px 20px';
            toast.style.borderRadius = '8px';
            toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            toast.style.zIndex = '9999';
            toast.style.fontFamily = 'var(--font-sans, sans-serif)';
            toast.style.fontSize = '14px';
            toast.style.fontWeight = '500';
            toast.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '12px';

            // Message text
            const msgText = document.createElement('span');
            msgText.innerText = data.message;
            toast.appendChild(msgText);

            // Dismiss button
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.background = 'transparent';
            closeBtn.style.border = 'none';
            closeBtn.style.color = '#ffffff';
            closeBtn.style.fontSize = '20px';
            closeBtn.style.lineHeight = '1';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.opacity = '0.8';
            closeBtn.style.padding = '0';
            closeBtn.style.marginLeft = '8px';
            
            closeBtn.onmouseover = () => closeBtn.style.opacity = '1';
            closeBtn.onmouseout = () => closeBtn.style.opacity = '0.8';
            
            closeBtn.onclick = () => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            };
            
            toast.appendChild(closeBtn);
            document.body.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            // Remove after 3 seconds automatically
            setTimeout(() => {
                if(document.body.contains(toast)) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        if(document.body.contains(toast)) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 3000);

            // Optionally update the stat counters on the page dynamically
            if(typeof updateKpiStats === 'function') {
                // updateKpiStats(status);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update status. Please try again.');
    });
}

// ── AJAX Attendance Update ─────────────────────────────
function updateAttendance(demoId, field, value) {
    if (value === "") value = null;
    else value = parseInt(value, 10);

    const payload = {};
    payload[field] = value;

    fetch(`/admin/demos/${demoId}/attendance`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast !== 'undefined') showToast('Attendance updated successfully', 'success');
            else alert('Attendance updated successfully');
        } else {
            alert('Failed to update attendance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating attendance');
    });
}
</script>
@endpush
