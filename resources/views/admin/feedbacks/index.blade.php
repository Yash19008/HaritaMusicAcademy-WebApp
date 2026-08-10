@extends('layouts.main')
@section('title', 'Feedbacks & Issues - Harita Music Academy')
@section('page', 'feedbacks')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
<div class="card">
        <div class="card-header d-flex align-center justify-between">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">All Submitted Feedbacks
            & Issues</h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="adminFbTable" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Category</th>
                <th>Target Element</th>
                <th>Rating</th>
                <th>Review Message</th>
                <th>Status</th>
                <th style="width: 100px; text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($feedbacks as $fb)
              <tr>
                <td>{{ $fb->created_at->format('Y-m-d') }}</td>
                <td class="font-semibold">{{ $fb->student ? $fb->student->name : 'Unknown' }}</td>
                <td><span class="badge badge-info">{{ $fb->category }}</span></td>
                <td class="font-medium">
                  @if($fb->category === 'Mentor' && $fb->teacher)
                      {{ $fb->teacher->name }}
                  @else
                      {{ $fb->target_element ?? '-' }}
                  @endif
                </td>
                <td class="font-bold text-primary">{{ str_repeat('★', $fb->rating) }}</td>
                <td style="white-space: normal; min-width: 200px;">{{ $fb->message }}</td>
                <td>
                  @if($fb->status === 'resolved')
                    <span class="badge badge-success">Resolved</span>
                  @else
                    <span class="badge badge-warning">Active</span>
                  @endif
                </td>
                <td style="text-align: center;">
                  @if($fb->status !== 'resolved')
                    <form action="{{ route('admin.feedbacks.status', $fb->id) }}" method="POST" style="display:inline-block;">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="resolved">
                      <button type="submit" class="btn btn-primary btn-sm p-1 px-2" style="font-size:0.75rem;" onclick="return confirm('Mark this feedback as resolved?');">Resolve</button>
                    </form>
                  @else
                    <span class="text-muted" style="font-size:0.85rem;">None</span>
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
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
      $('#adminFbTable').DataTable({ responsive: true });
    });
</script>
@endpush
