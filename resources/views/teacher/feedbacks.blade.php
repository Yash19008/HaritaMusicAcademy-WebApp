@extends('layouts.main')
@section('title', 'Feedbacks - Harita Music Academy')
@section('page', 'feedbacks')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@section('content')
<div class="card">
        <div class="card-header d-flex align-center justify-between">
          <h4 class="font-semibold" style="font-family: var(--font-serif); font-size: 1.25rem;">Feedbacks Given to Me
          </h4>
        </div>
        <div class="card-body p-3">
          <table class="table display responsive nowrap" id="teacherFbTable" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Student Name</th>
                <th>Rating</th>
                <th>Review Message</th>
              </tr>
            </thead>
            <tbody>
              @foreach($feedbacks as $fb)
              <tr>
                <td>{{ $fb->created_at->format('Y-m-d') }}</td>
                <td class="font-semibold">{{ $fb->student ? $fb->student->name : 'Unknown' }}</td>
                <td class="font-bold text-primary">{{ str_repeat('★', $fb->rating) }}</td>
                <td style="white-space: normal; min-width: 250px;">{{ $fb->message }}</td>
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
      $('#teacherFbTable').DataTable({ responsive: true });
    });
</script>
@endpush
