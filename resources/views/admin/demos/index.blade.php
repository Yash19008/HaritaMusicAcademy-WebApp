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

@if(session('success'))
    <div class="alert alert-success" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
                    <form action="{{ route('admin.demos.status', $demo) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control badge-select" onchange="this.form.submit()" style="
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
</script>
@endpush
